<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Session;

class MasterDataController extends Controller
{
    /**
     * Display Master Data index (Tahun Ajaran & Kelas).
     */
    public function index(Request $request)
    {
        $daftarTahunAjaran = TahunAjaran::withCount('spmbSets')->orderBy('is_active', 'desc')->orderBy('nama', 'desc')->get();
        $activeTa = TahunAjaran::where('is_active', true)->first();

        $pendaftaranQuery = Pendaftaran::query();
        if ($activeTa) {
            $pendaftaranQuery->whereHas('jalur', function($q) use ($activeTa) {
                $q->where('tahun_ajaran_id', $activeTa->id);
            });
        }

        // Get unique classes from Pendaftaran in active Tahun Ajaran
        $existingKelas = (clone $pendaftaranQuery)->whereNotNull('kelas')->where('kelas', '!=', '')->pluck('kelas')->toArray();
        $defaultKelas = ['X IPA 1', 'X IPA 2', 'X IPA 3', 'X IPS 1', 'X IPS 2', 'X IPS 3', 'XI IPA 1', 'XI IPA 2', 'XI IPA 3', 'XI IPS 1', 'XI IPS 2', 'XI IPS 3', 'XII IPA 1', 'XII IPA 2', 'XII IPA 3', 'XII IPS 1', 'XII IPS 2', 'XII IPS 3'];
        $daftarKelas = array_values(array_unique(array_merge($defaultKelas, $existingKelas)));
        sort($daftarKelas);

        $kelasStats = collect($daftarKelas)->map(function($namaKelas) use ($pendaftaranQuery) {
            $totalSiswa = (clone $pendaftaranQuery)->where('kelas', $namaKelas)->count();
            $diterimaCount = (clone $pendaftaranQuery)->where('status', 'diterima')->where('kelas', $namaKelas)->count();
            return [
                'nama' => $namaKelas,
                'total_siswa' => $totalSiswa,
                'diterima_count' => $diterimaCount,
            ];
        });

        return view('spmb.master-data', compact('daftarTahunAjaran', 'activeTa', 'daftarKelas', 'kelasStats'));
    }

    /**
     * Store new Tahun Ajaran
     */
    public function storeTahunAjaran(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:20|unique:tahun_ajaran,nama',
            'semester' => 'required|in:Ganjil,Genap',
            'periode_mulai' => 'nullable|date',
            'periode_selesai' => 'nullable|date|after_or_equal:periode_mulai',
            'keterangan' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $isActive = $request->boolean('is_active');
        if ($isActive) {
            TahunAjaran::query()->update(['is_active' => false]);
        }

        TahunAjaran::create([
            'nama' => $request->nama,
            'semester' => $request->semester,
            'periode_mulai' => $request->periode_mulai,
            'periode_selesai' => $request->periode_selesai,
            'keterangan' => $request->keterangan,
            'is_active' => $isActive,
        ]);

        return back()->with('success', "Tahun Ajaran {$request->nama} ({$request->semester}) berhasil ditambahkan.");
    }

    /**
     * Update Tahun Ajaran
     */
    public function updateTahunAjaran(Request $request, $id)
    {
        $ta = TahunAjaran::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:20|unique:tahun_ajaran,nama,' . $id,
            'semester' => 'required|in:Ganjil,Genap',
            'periode_mulai' => 'nullable|date',
            'periode_selesai' => 'nullable|date|after_or_equal:periode_mulai',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $ta->update($request->only(['nama', 'semester', 'periode_mulai', 'periode_selesai', 'keterangan']));

        return back()->with('success', "Data Tahun Ajaran {$ta->nama} berhasil diperbarui.");
    }

    /**
     * Toggle or Set Active Tahun Ajaran
     */
    public function toggleActiveTahunAjaran($id)
    {
        $ta = TahunAjaran::findOrFail($id);

        TahunAjaran::query()->update(['is_active' => false]);
        $ta->update(['is_active' => true]);

        $settings = Session::get('spmb_settings', []);
        $settings['tahun_ajaran_id'] = $ta->id;
        $settings['tahun_ajaran'] = $ta->nama;
        Session::put('spmb_settings', $settings);

        return back()->with('success', "Tahun Ajaran {$ta->nama} ({$ta->semester}) telah diset sebagai Tahun Ajaran AKTIF.");
    }

    /**
     * Delete Tahun Ajaran
     */
    public function destroyTahunAjaran($id)
    {
        $ta = TahunAjaran::findOrFail($id);

        if ($ta->spmbSets()->count() > 0) {
            return back()->with('error', "Tahun Ajaran {$ta->nama} tidak dapat dihapus karena masih terhubung dengan {$ta->spmbSets()->count()} jalur pendaftaran.");
        }

        if ($ta->is_active) {
            return back()->with('error', "Tahun Ajaran aktif tidak dapat dihapus. Silakan aktifkan tahun ajaran lain terlebih dahulu.");
        }

        $ta->delete();

        return back()->with('success', "Tahun Ajaran {$ta->nama} berhasil dihapus.");
    }

    /**
     * Halaman Kelola & Alokasi Kelas
     */
    public function kelas(Request $request)
    {
        $query = Pendaftaran::with('jalur')->where('status', 'diterima');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kelas')) {
            if ($request->kelas === 'belum') {
                $query->whereNull('kelas')->orWhere('kelas', '');
            } else {
                $query->where('kelas', $request->kelas);
            }
        }

        $diterimaList = $query->paginate(15)->withQueryString();

        $totalDiterima = Pendaftaran::where('status', 'diterima')->count();
        $teralokasi = Pendaftaran::where('status', 'diterima')->whereNotNull('kelas')->where('kelas', '!=', '')->count();
        $belumAlokasi = $totalDiterima - $teralokasi;

        $existingKelas = Pendaftaran::whereNotNull('kelas')->where('kelas', '!=', '')->pluck('kelas')->toArray();
        $defaultKelas = ['X IPA 1', 'X IPA 2', 'X IPA 3', 'X IPS 1', 'X IPS 2', 'X IPS 3', 'XI IPA 1', 'XI IPA 2', 'XI IPA 3', 'XI IPS 1', 'XI IPS 2', 'XI IPS 3', 'XII IPA 1', 'XII IPA 2', 'XII IPA 3', 'XII IPS 1', 'XII IPS 2', 'XII IPS 3'];
        $daftarKelas = array_values(array_unique(array_merge($defaultKelas, $existingKelas)));
        sort($daftarKelas);

        return view('spmb.manajemen-kelas', compact('diterimaList', 'totalDiterima', 'teralokasi', 'belumAlokasi', 'daftarKelas'));
    }

    /**
     * Proses Update Kelas Single / Batch
     */
    public function updateKelas(Request $request)
    {
        $request->validate([
            'pendaftaran_ids' => 'required|array',
            'pendaftaran_ids.*' => 'exists:spmb,id',
            'kelas' => 'required|string|max:50',
        ]);

        $ids = $request->pendaftaran_ids;
        $kelas = $request->kelas;

        Pendaftaran::whereIn('id', $ids)->update(['kelas' => $kelas]);

        $count = count($ids);
        return back()->with('success', "Berhasil memperbarui alokasi kelas untuk {$count} calon siswa ke kelas {$kelas}.");
    }
}

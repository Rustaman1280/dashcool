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

        // Get all unique classes from Pendaftaran
        $existingKelas = Pendaftaran::whereNotNull('kelas')->where('kelas', '!=', '')->pluck('kelas')->toArray();
        $defaultKelas = ['X IPA 1', 'X IPA 2', 'X IPS 1', 'X IPS 2', 'VII A', 'VII B', 'VII C', 'X RPL 1', 'X TITL 1'];
        $daftarKelas = array_values(array_unique(array_merge($defaultKelas, $existingKelas)));
        sort($daftarKelas);

        $kelasStats = collect($daftarKelas)->map(function($namaKelas) {
            $totalSiswa = Pendaftaran::where('kelas', $namaKelas)->count();
            $diterimaCount = Pendaftaran::where('status', 'diterima')->where('kelas', $namaKelas)->count();
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
}

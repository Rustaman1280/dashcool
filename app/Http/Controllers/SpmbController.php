<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\SpmbSet;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class SpmbController extends Controller
{
    /**
     * Dashboard SPMB Online (index)
     */
    public function index()
    {
        $totalCount = Pendaftaran::count();
        $diverifikasiCount = Pendaftaran::where('status', 'diverifikasi')->count();
        $diterimaCount = Pendaftaran::where('status', 'diterima')->count();
        $ditolakCount = Pendaftaran::where('status', 'ditolak')->count();
        $menungguCount = Pendaftaran::where('status', 'menunggu')->count();

        $stats = [
            'total' => [
                'value' => number_format($totalCount, 0, ',', '.'),
                'change' => '+14.2% minggu ini',
                'changeType' => 'increase',
                'subtitle' => 'Target: 475 Kuota'
            ],
            'diverifikasi' => [
                'value' => number_format($diverifikasiCount, 0, ',', '.'),
                'change' => $totalCount > 0 ? round(($diverifikasiCount / $totalCount) * 100, 1) . '% dari total' : '0%',
                'changeType' => 'increase',
                'subtitle' => 'Telah terverifikasi'
            ],
            'diterima' => [
                'value' => number_format($diterimaCount, 0, ',', '.'),
                'change' => 'Gelombang I',
                'changeType' => 'increase',
                'subtitle' => 'Lolos seleksi'
            ],
            'ditolak' => [
                'value' => number_format($ditolakCount, 0, ',', '.'),
                'change' => 'Berkas tidak sesuai',
                'changeType' => 'decrease',
                'subtitle' => 'Perlu perbaikan'
            ],
        ];

        // Fetch Jalur Pendaftaran with live counts
        $jalurs = SpmbSet::with('tahunAjaran')->withCount('pendaftarans')->get();
        $kuotas = $jalurs->map(function ($j) {
            $terisi = $j->pendaftarans_count;
            $persen = $j->kuota > 0 ? round(($terisi / $j->kuota) * 100, 1) : 0;
            $sisa = max($j->kuota - $terisi, 0);

            $statusText = $persen >= 90 ? 'Mendekati Penuh' : ($sisa <= 15 ? "Sisa {$sisa} Kursi" : 'Tersedia');
            $color = match($j->kode_jalur) {
                'REG' => 'bg-indigo-600',
                'PRS' => 'bg-sky-600',
                'AFR' => 'bg-emerald-600',
                default => 'bg-amber-600'
            };

            return [
                'nama' => $j->nama_jalur,
                'terisi' => $terisi,
                'total' => $j->kuota,
                'persen' => $persen,
                'color' => $color,
                'status' => $statusText,
            ];
        });

        // Chart Data
        $labels = [];
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->translatedFormat('D, d M');
            $days[] = $date->toDateString();
        }

        $regData = [];
        $prsData = [];
        $afrData = [];

        $regJalur = $jalurs->where('kode_jalur', 'REG')->first();
        $prsJalur = $jalurs->where('kode_jalur', 'PRS')->first();
        $afrJalur = $jalurs->where('kode_jalur', 'AFR')->first();

        foreach ($days as $day) {
            $regData[] = Pendaftaran::whereDate('created_at', $day)
                ->when($regJalur, fn($q) => $q->where('jalur_id', $regJalur->id))
                ->count() + rand(2, 5);

            $prsData[] = Pendaftaran::whereDate('created_at', $day)
                ->when($prsJalur, fn($q) => $q->where('jalur_id', $prsJalur->id))
                ->count() + rand(1, 3);

            $afrData[] = Pendaftaran::whereDate('created_at', $day)
                ->when($afrJalur, fn($q) => $q->where('jalur_id', $afrJalur->id))
                ->count() + rand(0, 2);
        }

        $chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jalur Reguler',
                    'data' => $regData,
                    'borderColor' => '#4f46e5',
                    'backgroundColor' => 'rgba(79, 70, 229, 0.1)',
                ],
                [
                    'label' => 'Jalur Prestasi',
                    'data' => $prsData,
                    'borderColor' => '#0284c7',
                    'backgroundColor' => 'rgba(2, 132, 199, 0.1)',
                ],
                [
                    'label' => 'Jalur Afirmasi',
                    'data' => $afrData,
                    'borderColor' => '#059669',
                    'backgroundColor' => 'rgba(5, 150, 105, 0.1)',
                ]
            ]
        ];

        // Recent pendaftar
        $pendaftarTerbaru = Pendaftaran::with('jalur')
            ->latest()
            ->take(6)
            ->get();

        return view('spmb.index', compact('stats', 'chartData', 'kuotas', 'pendaftarTerbaru', 'totalCount', 'menungguCount'));
    }

    /**
     * 1. INPUT SPMB - Form Tambah Pendaftar Baru
     */
    public function create()
    {
        $jalurs = SpmbSet::with('tahunAjaran')->where('status', 'aktif')->get();
        if ($jalurs->isEmpty()) {
            $jalurs = SpmbSet::with('tahunAjaran')->get();
        }

        $nextNumber = 'SPMB-' . date('Y') . '-' . sprintf('%03d', Pendaftaran::count() + 1);

        return view('spmb.input', compact('jalurs', 'nextNumber'));
    }

    /**
     * 1. INPUT SPMB - Process Store Pendaftar Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:spmb,nisn',
            'nik' => 'nullable|string|max:16',
            'no_kk' => 'nullable|string|max:16',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:50',
            'alamat' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'asal_sekolah' => 'required|string|max:255',
            'npsn_asal' => 'nullable|string|max:20',
            'nama_ayah' => 'required|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'no_hp_ayah' => 'nullable|string|max:20',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'no_hp_ibu' => 'nullable|string|max:20',
            'jalur_id' => 'required|exists:spmb_set,id',
            'catatan_verifikasi' => 'nullable|string',
            'berkas_foto' => 'nullable|file|mimes:jpeg,jpg,png,webp|max:3072',
            'berkas_kk' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5120',
            'berkas_akta' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5120',
            'berkas_ijazah' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5120',
            'berkas_sertifikat' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5120',
        ]);

        $validated['no_pendaftaran'] = 'SPMB-' . date('Y') . '-' . sprintf('%03d', Pendaftaran::count() + 1);
        $validated['status'] = 'menunggu';

        // Process Document File Uploads
        $dokumen = [];
        $docMap = [
            'berkas_foto' => 'Pas Foto Siswa',
            'berkas_kk' => 'Kartu Keluarga (KK)',
            'berkas_akta' => 'Akta Kelahiran',
            'berkas_ijazah' => 'Ijazah / SKL / Rapor',
            'berkas_sertifikat' => 'Sertifikat Prestasi',
        ];

        foreach ($docMap as $inputKey => $label) {
            if ($request->hasFile($inputKey)) {
                $file = $request->file($inputKey);
                $fileName = time() . '_' . $inputKey . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('spmb_dokumen/' . $validated['no_pendaftaran'], $fileName, 'public');

                $dokumen[$inputKey] = [
                    'key' => $inputKey,
                    'label' => $label,
                    'filename' => $fileName,
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'url' => asset('storage/' . $path),
                    'size' => round($file->getSize() / 1024, 1) . ' KB',
                    'mime' => $file->getClientMimeType(),
                    'uploaded_at' => now()->toDateTimeString(),
                ];
            }
        }

        $validated['dokumen'] = !empty($dokumen) ? $dokumen : null;

        $pendaftar = Pendaftaran::create($validated);

        return redirect()->route('spmb.detail', $pendaftar->id)
            ->with('success', "Pendaftaran calon siswa {$pendaftar->nama_lengkap} berhasil disimpan dengan nomor {$pendaftar->no_pendaftaran}!");
    }

    /**
     * Data & Verifikasi Pendaftar
     */
    public function pendaftar(Request $request)
    {
        $query = Pendaftaran::with('jalur.tahunAjaran');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'like', "%{$search}%")
                  ->orWhere('asal_sekolah', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jalur_id')) {
            $query->where('jalur_id', $request->jalur_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $pendaftarList = $query->latest()->paginate(10)->withQueryString();
        $jalurList = SpmbSet::all();

        $counts = [
            'total' => Pendaftaran::count(),
            'menunggu' => Pendaftaran::where('status', 'menunggu')->count(),
            'diverifikasi' => Pendaftaran::where('status', 'diverifikasi')->count(),
            'diterima' => Pendaftaran::where('status', 'diterima')->count(),
            'ditolak' => Pendaftaran::where('status', 'ditolak')->count(),
        ];

        return view('spmb.pendaftar', compact('pendaftarList', 'jalurList', 'counts'));
    }

    /**
     * Detail Pendaftar SPMB
     */
    public function detail($id = 1)
    {
        $pendaftar = Pendaftaran::with('jalur')->find($id);

        if (!$pendaftar) {
            $pendaftar = Pendaftaran::with('jalur')->first();
        }

        return view('spmb.detail', compact('pendaftar'));
    }

    /**
     * Update status pendaftaran (Verifikasi / Terima / Tolak)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diverifikasi,diterima,ditolak',
            'catatan_verifikasi' => 'nullable|string|max:500',
        ]);

        $pendaftar = Pendaftaran::findOrFail($id);
        $pendaftar->update([
            'status' => $request->status,
            'catatan_verifikasi' => $request->catatan_verifikasi,
        ]);

        $statusLabels = [
            'diverifikasi' => 'diverifikasi',
            'diterima' => 'diterima sebagai calon siswa baru',
            'ditolak' => 'ditolak',
            'menunggu' => 'dikembalikan ke antrean verifikasi',
        ];

        return back()->with('success', "Status pendaftar {$pendaftar->nama_lengkap} ({$pendaftar->no_pendaftaran}) berhasil diperbarui menjadi {$statusLabels[$request->status]}.");
    }

    /**
     * 2. REKAP SPMB - Laporan & Rekapitulasi Analytics
     */
    public function rekap(Request $request)
    {
        $query = Pendaftaran::with('jalur');

        if ($request->filled('jalur_id')) {
            $query->where('jalur_id', $request->jalur_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $allPendaftar = $query->get();
        $totalPendaftar = $allPendaftar->count();

        // Rekap Status
        $rekapStatus = [
            'menunggu' => $allPendaftar->where('status', 'menunggu')->count(),
            'diverifikasi' => $allPendaftar->where('status', 'diverifikasi')->count(),
            'diterima' => $allPendaftar->where('status', 'diterima')->count(),
            'ditolak' => $allPendaftar->where('status', 'ditolak')->count(),
        ];

        // Rekap Jalur
        $jalurs = SpmbSet::with('tahunAjaran')->withCount('pendaftarans')->get();
        $rekapJalur = $jalurs->map(function ($j) {
            $terisi = $j->pendaftarans_count;
            $persen = $j->kuota > 0 ? round(($terisi / $j->kuota) * 100, 1) : 0;
            return [
                'id' => $j->id,
                'nama' => $j->nama_jalur,
                'kode' => $j->kode_jalur,
                'kuota' => $j->kuota,
                'terisi' => $terisi,
                'sisa' => max($j->kuota - $terisi, 0),
                'persen' => $persen,
            ];
        });

        // Rekap Gender
        $rekapGender = [
            'L' => $allPendaftar->where('jenis_kelamin', 'L')->count(),
            'P' => $allPendaftar->where('jenis_kelamin', 'P')->count(),
        ];

        // Rekap Asal Sekolah (Top 10)
        $rekapSekolah = $allPendaftar->groupBy('asal_sekolah')->map(function ($group, $sekolah) {
            return [
                'sekolah' => $sekolah,
                'total' => $group->count(),
                'diterima' => $group->where('status', 'diterima')->count(),
            ];
        })->sortByDesc('total')->take(10);

        // Rekap Kelas Calon Siswa
        $diterimaList = Pendaftaran::where('status', 'diterima')->get();
        $rekapKelas = $diterimaList->groupBy(function ($item) {
            return $item->kelas ?: 'Belum Ada Kelas';
        })->map(function ($group, $kelas) {
            return [
                'kelas' => $kelas,
                'total' => $group->count(),
            ];
        });

        $jalurList = SpmbSet::all();

        return view('spmb.rekap', compact(
            'totalPendaftar', 'rekapStatus', 'rekapJalur', 
            'rekapGender', 'rekapSekolah', 'rekapKelas', 
            'jalurList', 'allPendaftar'
        ));
    }

    /**
     * 2. REKAP SPMB - Export Data CSV
     */
    public function exportRekap(Request $request)
    {
        $pendaftarList = Pendaftaran::with('jalur')->latest()->get();

        $filename = "rekap_spmb_" . date('Y-m-d_H-i') . ".csv";

        $handle = fopen('php://memory', 'w+');
        fputcsv($handle, [
            'No. Pendaftaran', 'NISN', 'Nama Lengkap', 'Jenis Kelamin', 
            'Asal Sekolah', 'Jalur Pendaftaran', 'Status Verifikasi', 'Kelas Diterima', 
            'Telepon', 'Email', 'Tanggal Daftar'
        ]);

        foreach ($pendaftarList as $p) {
            fputcsv($handle, [
                $p->no_pendaftaran,
                $p->nisn,
                $p->nama_lengkap,
                $p->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                $p->asal_sekolah,
                $p->jalur->nama_jalur ?? '-',
                strtoupper($p->status),
                $p->kelas ?: 'Belum Ada Kelas',
                $p->telepon ?: '-',
                $p->email ?: '-',
                $p->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * 4. SET SPMB - Pengaturan Sistem SPMB & Jalur
     */
    public function pengaturan()
    {
        $jalurs = SpmbSet::with('tahunAjaran')->withCount('pendaftarans')->get();
        $daftarTahunAjaran = TahunAjaran::withCount('spmbSets')->orderBy('is_active', 'desc')->orderBy('nama', 'desc')->get();
        $activeTa = TahunAjaran::where('is_active', true)->first();

        $sistemSettings = Session::get('spmb_settings', [
            'tahun_ajaran_id' => $activeTa?->id,
            'tahun_ajaran' => $activeTa?->nama ?? '2026/2027',
            'gelombang' => 'Gelombang I',
            'status_spmb' => 'aktif',
            'total_kuota' => 475,
            'periode_buka' => '2026-01-01',
            'periode_tutup' => '2026-08-30',
            'pengumuman' => 'Pendaftaran Gelombang I telah dibuka! Silakan lengkapi dokumen persyaratannya.',
            'syarat' => "1. Pas Foto 3x4 (2 Lembar)\n2. Fotokopi Kartu Keluarga\n3. Fotokopi Akta Kelahiran\n4. Surat Keterangan Lulus / Rapor Terakhir",
        ]);

        if (empty($sistemSettings['tahun_ajaran_id']) && $activeTa) {
            $sistemSettings['tahun_ajaran_id'] = $activeTa->id;
            $sistemSettings['tahun_ajaran'] = $activeTa->nama;
        }

        return view('spmb.pengaturan', compact('jalurs', 'daftarTahunAjaran', 'activeTa', 'sistemSettings'));
    }

    /**
     * 4. SET SPMB - Update Pengaturan Sistem SPMB
     */
    public function updateSistem(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'nullable|exists:tahun_ajaran,id',
            'tahun_ajaran' => 'nullable|string|max:20',
            'gelombang' => 'required|string|max:50',
            'status_spmb' => 'required|in:aktif,tutup',
            'total_kuota' => 'required|integer|min:1',
            'periode_buka' => 'required|date',
            'periode_tutup' => 'required|date|after_or_equal:periode_buka',
            'pengumuman' => 'nullable|string|max:1000',
            'syarat' => 'nullable|string|max:2000',
        ]);

        $tahunAjaranNama = $request->tahun_ajaran;
        if ($request->filled('tahun_ajaran_id')) {
            $ta = TahunAjaran::find($request->tahun_ajaran_id);
            if ($ta) {
                $tahunAjaranNama = $ta->nama;
            }
        }

        $data = $request->only([
            'tahun_ajaran_id', 'gelombang', 'status_spmb', 'total_kuota', 
            'periode_buka', 'periode_tutup', 'pengumuman', 'syarat'
        ]);
        $data['tahun_ajaran'] = $tahunAjaranNama;

        Session::put('spmb_settings', $data);

        return back()->with('success', 'Pengaturan sistem SPMB berhasil diperbarui.');
    }

    /**
     * Store new Jalur Pendaftaran
     */
    public function storeJalur(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'nullable|exists:tahun_ajaran,id',
            'nama_jalur' => 'required|string|max:255',
            'kode_jalur' => 'required|string|max:10|unique:spmb_set,kode_jalur',
            'kuota' => 'required|integer|min:1',
            'periode_buka' => 'required|date',
            'periode_tutup' => 'required|date|after_or_equal:periode_buka',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,tutup',
        ]);

        $data = $request->all();
        if (empty($data['tahun_ajaran_id'])) {
            $activeTa = TahunAjaran::where('is_active', true)->first();
            $data['tahun_ajaran_id'] = $activeTa?->id;
        }

        SpmbSet::create($data);

        return back()->with('success', 'Jalur pendaftaran baru berhasil ditambahkan.');
    }

    /**
     * Update Jalur Pendaftaran
     */
    public function updateJalur(Request $request, $id)
    {
        $jalur = SpmbSet::findOrFail($id);

        $request->validate([
            'tahun_ajaran_id' => 'nullable|exists:tahun_ajaran,id',
            'nama_jalur' => 'required|string|max:255',
            'kode_jalur' => 'required|string|max:10|unique:spmb_set,kode_jalur,' . $id,
            'kuota' => 'required|integer|min:1',
            'periode_buka' => 'required|date',
            'periode_tutup' => 'required|date|after_or_equal:periode_buka',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,tutup',
        ]);

        $jalur->update($request->all());

        return back()->with('success', "Pengaturan jalur {$jalur->nama_jalur} berhasil diperbarui.");
    }

    /**
     * Delete Jalur Pendaftaran
     */
    public function destroyJalur($id)
    {
        $jalur = SpmbSet::findOrFail($id);

        if ($jalur->pendaftarans()->count() > 0) {
            return back()->with('error', "Jalur {$jalur->nama_jalur} tidak dapat dihapus karena sudah memiliki pendaftar terdaftar.");
        }

        $jalur->delete();

        return back()->with('success', 'Jalur pendaftaran berhasil dihapus.');
    }

    /**
     * =========================================================================
     * PUBLIC SPMB PORTAL (KHUSUS SISWA & ORANG TUA - MOBILE FIRST)
     * =========================================================================
     */

    /**
     * 1. Public Register Form (Mobile-First)
     */
    public function publicRegister()
    {
        $activeTa = TahunAjaran::where('is_active', true)->first();
        
        $jalursQuery = SpmbSet::with('tahunAjaran')->withCount('pendaftarans')->where('status', 'aktif');
        if ($activeTa) {
            $jalurs = $jalursQuery->where('tahun_ajaran_id', $activeTa->id)->get();
            if ($jalurs->isEmpty()) {
                $jalurs = SpmbSet::with('tahunAjaran')->withCount('pendaftarans')->where('status', 'aktif')->get();
            }
        } else {
            $jalurs = $jalursQuery->get();
        }

        if ($jalurs->isEmpty()) {
            $jalurs = SpmbSet::with('tahunAjaran')->withCount('pendaftarans')->get();
        }

        $sistemSettings = Session::get('spmb_settings', [
            'tahun_ajaran_id' => $activeTa?->id,
            'tahun_ajaran' => $activeTa?->nama ?? '2026/2027',
            'gelombang' => 'Gelombang I',
            'status_spmb' => 'aktif',
            'total_kuota' => 475,
            'periode_buka' => '2026-01-01',
            'periode_tutup' => '2026-08-30',
            'pengumuman' => 'Pendaftaran Peserta Didik Baru telah dibuka! Silakan lengkapi formulir pendaftaran online dengan data yang benar.',
            'syarat' => "1. Pas Foto Calon Siswa\n2. Kartu Keluarga (KK)\n3. Akta Kelahiran\n4. Surat Keterangan Lulus / Rapor Terakhir",
        ]);

        $nextNumber = 'SPMB-' . date('Y') . '-' . sprintf('%03d', Pendaftaran::count() + 1);

        return view('spmb.public.register', compact('jalurs', 'activeTa', 'sistemSettings', 'nextNumber'));
    }

    /**
     * 2. Public Store Pendaftar
     */
    public function publicStore(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:spmb,nisn',
            'nik' => 'nullable|string|max:16',
            'no_kk' => 'nullable|string|max:16',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:50',
            'alamat' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'asal_sekolah' => 'required|string|max:255',
            'npsn_asal' => 'nullable|string|max:20',
            'nama_ayah' => 'required|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'no_hp_ayah' => 'nullable|string|max:20',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'no_hp_ibu' => 'nullable|string|max:20',
            'jalur_id' => 'required|exists:spmb_set,id',
            'berkas_foto' => 'nullable|file|mimes:jpeg,jpg,png,webp|max:3072',
            'berkas_kk' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5120',
            'berkas_akta' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5120',
            'berkas_ijazah' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5120',
            'berkas_sertifikat' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5120',
        ], [
            'nama_lengkap.required' => 'Nama lengkap calon siswa wajib diisi.',
            'nisn.required' => 'Nomor Induk Siswa Nasional (NISN) wajib diisi.',
            'nisn.unique' => 'NISN ini sudah terdaftar di dalam sistem SPMB.',
            'jenis_kelamin.required' => 'Pilih jenis kelamin calon siswa.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'agama.required' => 'Pilihan agama wajib diisi.',
            'alamat.required' => 'Alamat domisili tempat tinggal wajib diisi.',
            'asal_sekolah.required' => 'Nama sekolah asal (SMP/MTs) wajib diisi.',
            'nama_ayah.required' => 'Nama ayah kandung wajib diisi.',
            'nama_ibu.required' => 'Nama ibu kandung wajib diisi.',
            'jalur_id.required' => 'Silakan pilih salah satu jalur pendaftaran yang tersedia.',
            'berkas_foto.max' => 'Ukuran Pas Foto maksimal 3MB.',
            'berkas_foto.mimes' => 'Format Pas Foto harus JPG, PNG, atau WebP.',
            'berkas_kk.max' => 'Ukuran file Kartu Keluarga maksimal 5MB.',
            'berkas_kk.mimes' => 'Format file Kartu Keluarga harus PDF, JPG, atau PNG.',
            'berkas_akta.max' => 'Ukuran file Akta Kelahiran maksimal 5MB.',
            'berkas_akta.mimes' => 'Format file Akta Kelahiran harus PDF, JPG, atau PNG.',
            'berkas_ijazah.max' => 'Ukuran file Ijazah/SKL maksimal 5MB.',
            'berkas_ijazah.mimes' => 'Format file Ijazah/SKL harus PDF, JPG, atau PNG.',
            'berkas_sertifikat.max' => 'Ukuran file Sertifikat maksimal 5MB.',
            'berkas_sertifikat.mimes' => 'Format file Sertifikat harus PDF, JPG, atau PNG.',
        ]);

        $validated['no_pendaftaran'] = 'SPMB-' . date('Y') . '-' . sprintf('%03d', Pendaftaran::count() + 1);
        $validated['status'] = 'menunggu';

        // Process Document File Uploads
        $dokumen = [];
        $docMap = [
            'berkas_foto' => 'Pas Foto Siswa',
            'berkas_kk' => 'Kartu Keluarga (KK)',
            'berkas_akta' => 'Akta Kelahiran',
            'berkas_ijazah' => 'Ijazah / SKL / Rapor',
            'berkas_sertifikat' => 'Sertifikat Prestasi',
        ];

        foreach ($docMap as $inputKey => $label) {
            if ($request->hasFile($inputKey)) {
                $file = $request->file($inputKey);
                $fileName = time() . '_' . $inputKey . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('spmb_dokumen/' . $validated['no_pendaftaran'], $fileName, 'public');

                $dokumen[$inputKey] = [
                    'key' => $inputKey,
                    'label' => $label,
                    'filename' => $fileName,
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'url' => asset('storage/' . $path),
                    'size' => round($file->getSize() / 1024, 1) . ' KB',
                    'mime' => $file->getClientMimeType(),
                    'uploaded_at' => now()->toDateTimeString(),
                ];
            }
        }

        $validated['dokumen'] = !empty($dokumen) ? $dokumen : null;

        $pendaftar = Pendaftaran::create($validated);

        return redirect()->route('spmb.public.success', $pendaftar->id)
            ->with('success', "Selamat! Formulir pendaftaran berhasil dikirim dengan Nomor: {$pendaftar->no_pendaftaran}");
    }

    /**
     * 3. Public Success Registration Screen & Digital Card
     */
    public function publicSuccess($id)
    {
        $pendaftar = Pendaftaran::with('jalur.tahunAjaran')->findOrFail($id);

        $sistemSettings = Session::get('spmb_settings', [
            'tahun_ajaran' => '2026/2027',
            'gelombang' => 'Gelombang I',
        ]);

        return view('spmb.public.success', compact('pendaftar', 'sistemSettings'));
    }

    /**
     * 4. Public Check Status SPMB
     */
    public function publicStatus(Request $request)
    {
        $searchQuery = trim($request->input('search', ''));
        $pendaftar = null;

        if (!empty($searchQuery)) {
            $pendaftar = Pendaftaran::with('jalur.tahunAjaran')
                ->where(function ($q) use ($searchQuery) {
                    $q->where('nisn', $searchQuery)
                      ->orWhere('no_pendaftaran', $searchQuery)
                      ->orWhere('no_pendaftaran', 'like', "%{$searchQuery}%");
                })
                ->first();
        }

        $sistemSettings = Session::get('spmb_settings', [
            'tahun_ajaran' => '2026/2027',
            'gelombang' => 'Gelombang I',
            'pengumuman' => 'Hasil seleksi berkas dan pengumuman kelas diumumkan secara berkala.',
        ]);

        return view('spmb.public.status', compact('pendaftar', 'searchQuery', 'sistemSettings'));
    }

    /**
     * 5. Public Process Check Status Search
     */
    public function publicCheckStatus(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|max:50',
        ], [
            'keyword.required' => 'Masukkan NISN atau Nomor Pendaftaran Anda.',
        ]);

        $keyword = trim($request->keyword);

        $pendaftar = Pendaftaran::where('nisn', $keyword)
            ->orWhere('no_pendaftaran', $keyword)
            ->orWhere('no_pendaftaran', 'like', "%{$keyword}%")
            ->first();

        if (!$pendaftar) {
            return redirect()->route('spmb.public.status', ['search' => $keyword])
                ->with('error', "Data pendaftaran dengan NISN/Nomor '{$keyword}' tidak ditemukan. Mohon periksa kembali nomor Anda.");
        }

        return redirect()->route('spmb.public.status', ['search' => $keyword])
            ->with('success', 'Data pendaftaran ditemukan!');
    }

    /**
     * 6. Public Printable Proof of Registration (Cetak Bukti SPMB)
     */
    public function publicProof($id)
    {
        $pendaftar = Pendaftaran::with('jalur.tahunAjaran')->findOrFail($id);

        $sistemSettings = Session::get('spmb_settings', [
            'tahun_ajaran' => $pendaftar->jalur->tahunAjaran->nama ?? '2026/2027',
            'gelombang' => 'Gelombang I',
        ]);

        return view('spmb.public.bukti', compact('pendaftar', 'sistemSettings'));
    }
}

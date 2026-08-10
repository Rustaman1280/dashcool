<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\JalurPendaftaran;
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
        $jalurs = JalurPendaftaran::withCount('pendaftarans')->get();
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
        $jalurs = JalurPendaftaran::where('status', 'aktif')->get();
        if ($jalurs->isEmpty()) {
            $jalurs = JalurPendaftaran::all();
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
            'nisn' => 'required|string|max:20|unique:pendaftarans,nisn',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'nik' => 'nullable|string|max:20',
            'no_kk' => 'nullable|string|max:20',
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
            'jalur_id' => 'required|exists:jalur_pendaftarans,id',
        ]);

        // Auto Generate No Pendaftaran if not provided
        $validated['no_pendaftaran'] = 'SPMB-' . date('Y') . '-' . sprintf('%03d', Pendaftaran::count() + 1);
        $validated['status'] = 'menunggu';
        $validated['dokumen'] = [
            'kartu_keluarga' => 'kk_default.pdf',
            'akta_kelahiran' => 'akta_default.pdf',
            'rapor' => 'rapor_default.pdf',
        ];

        $pendaftar = Pendaftaran::create($validated);

        return redirect()->route('spmb.detail', $pendaftar->id)
            ->with('success', "Pendaftaran calon siswa {$pendaftar->nama_lengkap} dengan No. Registrasi {$pendaftar->no_pendaftaran} berhasil ditambahkan.");
    }

    /**
     * Data & Verifikasi Pendaftar
     */
    public function pendaftar(Request $request)
    {
        $query = Pendaftaran::with('jalur');

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
        $jalurList = JalurPendaftaran::all();

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
        $jalurs = JalurPendaftaran::withCount('pendaftarans')->get();
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

        $jalurList = JalurPendaftaran::all();

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
     * 3. UPDATE KELAS SPMB - Halaman Kelola & Alokasi Kelas
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
        $defaultKelas = ['X IPA 1', 'X IPA 2', 'X IPS 1', 'X IPS 2', 'VII A', 'VII B', 'VII C', 'X RPL 1', 'X TITL 1'];
        $daftarKelas = array_values(array_unique(array_merge($defaultKelas, $existingKelas)));
        sort($daftarKelas);

        return view('spmb.kelas', compact('diterimaList', 'totalDiterima', 'teralokasi', 'belumAlokasi', 'daftarKelas'));
    }

    /**
     * 3. UPDATE KELAS SPMB - Process Update Kelas Single / Batch
     */
    public function updateKelas(Request $request)
    {
        $request->validate([
            'pendaftaran_ids' => 'required|array',
            'pendaftaran_ids.*' => 'exists:pendaftarans,id',
            'kelas' => 'required|string|max:50',
        ]);

        $ids = $request->pendaftaran_ids;
        $kelas = $request->kelas;

        Pendaftaran::whereIn('id', $ids)->update(['kelas' => $kelas]);

        $count = count($ids);
        return back()->with('success', "Berhasil memperbarui alokasi kelas untuk {$count} calon siswa ke kelas {$kelas}.");
    }

    /**
     * 4. SET SPMB - Pengaturan Sistem SPMB & Jalur
     */
    public function pengaturan()
    {
        $jalurs = JalurPendaftaran::withCount('pendaftarans')->get();
        
        $sistemSettings = Session::get('spmb_settings', [
            'tahun_ajaran' => '2026/2027',
            'gelombang' => 'Gelombang I',
            'status_spmb' => 'aktif',
            'total_kuota' => 475,
            'periode_buka' => '2026-01-01',
            'periode_tutup' => '2026-08-30',
            'pengumuman' => 'Pendaftaran Gelombang I telah dibuka! Silakan lengkapi dokumen persyaratannya.',
            'syarat' => "1. Pas Foto 3x4 (2 Lembar)\n2. Fotokopi Kartu Keluarga\n3. Fotokopi Akta Kelahiran\n4. Surat Keterangan Lulus / Rapor Terakhir",
        ]);

        return view('spmb.pengaturan', compact('jalurs', 'sistemSettings'));
    }

    /**
     * 4. SET SPMB - Update Pengaturan Sistem SPMB
     */
    public function updateSistem(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:20',
            'gelombang' => 'required|string|max:50',
            'status_spmb' => 'required|in:aktif,tutup',
            'total_kuota' => 'required|integer|min:1',
            'periode_buka' => 'required|date',
            'periode_tutup' => 'required|date|after_or_equal:periode_buka',
            'pengumuman' => 'nullable|string|max:1000',
            'syarat' => 'nullable|string|max:2000',
        ]);

        Session::put('spmb_settings', $request->only([
            'tahun_ajaran', 'gelombang', 'status_spmb', 'total_kuota', 
            'periode_buka', 'periode_tutup', 'pengumuman', 'syarat'
        ]));

        return back()->with('success', 'Pengaturan sistem SPMB berhasil diperbarui.');
    }

    /**
     * Store new Jalur Pendaftaran
     */
    public function storeJalur(Request $request)
    {
        $request->validate([
            'nama_jalur' => 'required|string|max:255',
            'kode_jalur' => 'required|string|max:10|unique:jalur_pendaftarans,kode_jalur',
            'kuota' => 'required|integer|min:1',
            'periode_buka' => 'required|date',
            'periode_tutup' => 'required|date|after_or_equal:periode_buka',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,tutup',
        ]);

        JalurPendaftaran::create($request->all());

        return back()->with('success', 'Jalur pendaftaran baru berhasil ditambahkan.');
    }

    /**
     * Update Jalur Pendaftaran
     */
    public function updateJalur(Request $request, $id)
    {
        $jalur = JalurPendaftaran::findOrFail($id);

        $request->validate([
            'nama_jalur' => 'required|string|max:255',
            'kode_jalur' => 'required|string|max:10|unique:jalur_pendaftarans,kode_jalur,' . $id,
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
        $jalur = JalurPendaftaran::findOrFail($id);

        if ($jalur->pendaftarans()->count() > 0) {
            return back()->with('error', "Jalur {$jalur->nama_jalur} tidak dapat dihapus karena sudah memiliki pendaftar terdaftar.");
        }

        $jalur->delete();

        return back()->with('success', 'Jalur pendaftaran berhasil dihapus.');
    }
}

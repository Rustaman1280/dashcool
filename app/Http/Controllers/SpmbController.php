<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\JalurPendaftaran;
use Carbon\Carbon;

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

        // Chart Data (Counts per day for the last 7 days)
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
                ->count() + rand(2, 5); // baseline + live

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

        // Recent 6 pendaftar
        $pendaftarTerbaru = Pendaftaran::with('jalur')
            ->latest()
            ->take(6)
            ->get();

        return view('spmb.index', compact('stats', 'chartData', 'kuotas', 'pendaftarTerbaru', 'totalCount', 'menungguCount'));
    }

    /**
     * Daftar Pendaftar SPMB
     */
    public function pendaftar(Request $request)
    {
        $query = Pendaftaran::with('jalur');

        // Search by nama, NISN, or no_pendaftaran
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'like', "%{$search}%")
                  ->orWhere('asal_sekolah', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by jalur_id
        if ($request->filled('jalur_id')) {
            $query->where('jalur_id', $request->jalur_id);
        }

        // Filter by date
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
     * Pengaturan Jalur & Kuota
     */
    public function pengaturan()
    {
        $jalurs = JalurPendaftaran::withCount('pendaftarans')->get();
        return view('spmb.pengaturan', compact('jalurs'));
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

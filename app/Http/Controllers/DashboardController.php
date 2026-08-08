<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_siswa' => [
                'value' => '1.420',
                'change' => '+45 siswa baru',
                'changeType' => 'increase',
                'subtitle' => 'Aktif TA 2025/2026'
            ],
            'total_guru' => [
                'value' => '84',
                'change' => '100% Sertifikasi',
                'changeType' => 'increase',
                'subtitle' => 'Pengajar & Staf'
            ],
            'pendaftar_spmb' => [
                'value' => '1.248',
                'change' => '+14.2% minggu ini',
                'changeType' => 'increase',
                'subtitle' => 'Gelombang 1 Active'
            ],
            'kehadiran_today' => [
                'value' => '96.8%',
                'change' => '1.374 / 1.420 Hadir',
                'changeType' => 'increase',
                'subtitle' => 'Presensi Hari Ini'
            ]
        ];

        $pengumuman = [
            [
                'judul' => 'Pembukaan Pendaftaran SPMB Online TA 2026/2027',
                'tanggal' => '01 Aug 2026',
                'kategori' => 'SPMB',
                'penulis' => 'Panitia SPMB'
            ],
            [
                'judul' => 'Jadwal Rapat Evaluasi Akademik Semester Ganjil',
                'tanggal' => '05 Aug 2026',
                'kategori' => 'Akademik',
                'penulis' => 'Wakasek Kurikulum'
            ],
            [
                'judul' => 'Peremajaan Perangkat Laboratorium Komputer',
                'tanggal' => '07 Aug 2026',
                'kategori' => 'Inventaris',
                'penulis' => 'Sarpras'
            ],
        ];

        $quickAccess = [
            [
                'title' => 'SPMB Online',
                'desc' => 'Kelola pendaftaran, verifikasi berkas, & kuota penerimaan',
                'route' => route('spmb.index'),
                'icon' => 'spmb',
                'color' => 'bg-indigo-50 border-indigo-100 text-indigo-700 hover:border-indigo-300'
            ],
            [
                'title' => 'Master Data Siswa',
                'desc' => 'Database induk siswa, biodata, & riwayat kelas',
                'route' => '#',
                'icon' => 'siswa',
                'color' => 'bg-blue-50 border-blue-100 text-blue-700 hover:border-blue-300'
            ],
            [
                'title' => 'Presensi & Kehadiran',
                'desc' => 'Rekapitulasi kehadiran harian siswa & jam mengajar guru',
                'route' => '#',
                'icon' => 'kehadiran',
                'color' => 'bg-emerald-50 border-emerald-100 text-emerald-700 hover:border-emerald-300'
            ],
            [
                'title' => 'Laporan & Analytics',
                'desc' => 'Cetak laporan perkembangan sekolah & statistik',
                'route' => '#',
                'icon' => 'laporan',
                'color' => 'bg-amber-50 border-amber-100 text-amber-700 hover:border-amber-300'
            ],
        ];

        return view('dashboard', compact('stats', 'pengumuman', 'quickAccess'));
    }
}

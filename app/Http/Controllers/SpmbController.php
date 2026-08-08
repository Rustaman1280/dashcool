<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpmbController extends Controller
{
    /**
     * Dashboard SPMB Online (index)
     */
    public function index()
    {
        $stats = [
            'total' => [
                'value' => '1.248',
                'change' => '+14.2% dari minggu lalu',
                'changeType' => 'increase',
                'subtitle' => 'Target: 1.500 Kuota'
            ],
            'diverifikasi' => [
                'value' => '850',
                'change' => '+8.5% hari ini',
                'changeType' => 'increase',
                'subtitle' => '68.1% dari total'
            ],
            'diterima' => [
                'value' => '320',
                'change' => '+25 siswa baru',
                'changeType' => 'increase',
                'subtitle' => 'Gelombang I'
            ],
            'ditolak' => [
                'value' => '78',
                'change' => '-2.1% berkas tidak valid',
                'changeType' => 'decrease',
                'subtitle' => 'Perlu revisi data'
            ],
        ];

        $chartData = [
            'labels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            'datasets' => [
                [
                    'label' => 'Jalur Reguler',
                    'data' => [45, 68, 82, 95, 110, 140, 165],
                    'borderColor' => '#4f46e5', // indigo-600
                    'backgroundColor' => 'rgba(79, 70, 229, 0.1)',
                ],
                [
                    'label' => 'Jalur Prestasi',
                    'data' => [20, 32, 40, 48, 55, 70, 85],
                    'borderColor' => '#0284c7', // sky-600
                    'backgroundColor' => 'rgba(2, 132, 199, 0.1)',
                ],
                [
                    'label' => 'Jalur Afirmasi',
                    'data' => [10, 15, 18, 22, 28, 35, 42],
                    'borderColor' => '#059669', // emerald-600
                    'backgroundColor' => 'rgba(5, 150, 105, 0.1)',
                ]
            ]
        ];

        $kuotas = [
            [
                'nama' => 'Jalur Reguler (Zonasi & Umum)',
                'terisi' => 220,
                'total' => 300,
                'persen' => 73.3,
                'color' => 'bg-indigo-600',
                'status' => 'Mendekati Penuh'
            ],
            [
                'nama' => 'Jalur Prestasi (Akademik & Non-Akademik)',
                'terisi' => 85,
                'total' => 100,
                'persen' => 85.0,
                'color' => 'bg-sky-600',
                'status' => 'Sisa 15 Kursi'
            ],
            [
                'nama' => 'Jalur Afirmasi (KIP / Keluarga Sejahtera)',
                'terisi' => 42,
                'total' => 50,
                'persen' => 84.0,
                'color' => 'bg-emerald-600',
                'status' => 'Sisa 8 Kursi'
            ],
            [
                'nama' => 'Jalur Perpindahan Orang Tua / Mutasi',
                'terisi' => 15,
                'total' => 25,
                'persen' => 60.0,
                'color' => 'bg-amber-600',
                'status' => 'Tersedia'
            ],
        ];

        $pendaftarTerbaru = [
            [
                'id' => 1,
                'no_pendaftaran' => 'SPMB-2026-001',
                'nama' => 'Ahmad Fauzi',
                'nisn' => '0071234567',
                'asal_sekolah' => 'SMPN 1 Jakarta',
                'jalur' => 'Reguler',
                'status' => 'diverifikasi',
                'tanggal' => '08 Aug 2026, 09:30'
            ],
            [
                'id' => 2,
                'no_pendaftaran' => 'SPMB-2026-002',
                'nama' => 'Siti Nurhaliza',
                'nisn' => '0072345678',
                'asal_sekolah' => 'SMP Islam Al-Azhar 1',
                'jalur' => 'Prestasi',
                'status' => 'diterima',
                'tanggal' => '08 Aug 2026, 08:45'
            ],
            [
                'id' => 3,
                'no_pendaftaran' => 'SPMB-2026-003',
                'nama' => 'Budi Santoso',
                'nisn' => '0073456789',
                'asal_sekolah' => 'SMPN 5 Bandung',
                'jalur' => 'Afirmasi',
                'status' => 'menunggu',
                'tanggal' => '07 Aug 2026, 16:20'
            ],
            [
                'id' => 4,
                'no_pendaftaran' => 'SPMB-2026-004',
                'nama' => 'Riana Putri',
                'nisn' => '0074567890',
                'asal_sekolah' => 'SMP Maria Fidelis',
                'jalur' => 'Reguler',
                'status' => 'ditolak',
                'tanggal' => '07 Aug 2026, 14:15'
            ],
            [
                'id' => 5,
                'no_pendaftaran' => 'SPMB-2026-005',
                'nama' => 'Muhammad Rizky',
                'nisn' => '0075678901',
                'asal_sekolah' => 'SMPN 2 Bogor',
                'jalur' => 'Prestasi',
                'status' => 'diverifikasi',
                'tanggal' => '07 Aug 2026, 11:10'
            ],
            [
                'id' => 6,
                'no_pendaftaran' => 'SPMB-2026-006',
                'nama' => 'Clarissa Amalia',
                'nisn' => '0076789012',
                'asal_sekolah' => 'SMP Labschool Jakarta',
                'jalur' => 'Reguler',
                'status' => 'menunggu',
                'tanggal' => '06 Aug 2026, 15:40'
            ],
        ];

        return view('spmb.index', compact('stats', 'chartData', 'kuotas', 'pendaftarTerbaru'));
    }

    /**
     * Daftar Pendaftar SPMB
     */
    public function pendaftar()
    {
        return view('spmb.pendaftar');
    }

    /**
     * Detail Pendaftar SPMB
     */
    public function detail($id = 1)
    {
        return view('spmb.detail', compact('id'));
    }

    /**
     * Pengaturan Jalur & Kuota
     */
    public function pengaturan()
    {
        return view('spmb.pengaturan');
    }
}

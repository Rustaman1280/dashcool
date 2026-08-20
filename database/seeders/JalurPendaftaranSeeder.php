<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JalurPendaftaran;

class JalurPendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        $jalurs = [
            [
                'nama_jalur' => 'Jalur Reguler (Zonasi & Umum)',
                'kode_jalur' => 'REG',
                'kuota' => 300,
                'periode_buka' => '2026-01-01',
                'periode_tutup' => '2026-08-30',
                'deskripsi' => 'Pendaftaran berdasarkan domisili zonasi sekolah dan seleksi umum.',
                'status' => 'aktif',
            ],
            [
                'nama_jalur' => 'Jalur Prestasi (Akademik & Non-Akademik)',
                'kode_jalur' => 'PRS',
                'kuota' => 100,
                'periode_buka' => '2026-01-01',
                'periode_tutup' => '2026-08-15',
                'deskripsi' => 'Seleksi nilai rapor semester 1-5 dan sertifikat kejuaraan resmi.',
                'status' => 'aktif',
            ],
            [
                'nama_jalur' => 'Jalur Afirmasi (KIP / Keluarga Sejahtera)',
                'kode_jalur' => 'AFR',
                'kuota' => 50,
                'periode_buka' => '2026-01-01',
                'periode_tutup' => '2026-08-20',
                'deskripsi' => 'Khusus pemegang Kartu Indonesia Pintar (KIP) atau Program Keluarga Harapan (PKH).',
                'status' => 'aktif',
            ],
            [
                'nama_jalur' => 'Jalur Perpindahan Orang Tua / Mutasi',
                'kode_jalur' => 'MTS',
                'kuota' => 25,
                'periode_buka' => '2026-01-01',
                'periode_tutup' => '2026-08-25',
                'deskripsi' => 'Untuk calon peserta didik yang mengikuti perpindahan tugas orang tua/wali.',
                'status' => 'aktif',
            ],
        ];

        $activeTa = \App\Models\TahunAjaran::where('is_active', true)->first();

        foreach ($jalurs as $j) {
            if ($activeTa) {
                $j['tahun_ajaran_id'] = $activeTa->id;
            }
            JalurPendaftaran::updateOrCreate(['kode_jalur' => $j['kode_jalur']], $j);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TahunAjaran;
use App\Models\JalurPendaftaran;

class TahunAjaranSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => '2026/2027',
                'semester' => 'Ganjil',
                'is_active' => true,
                'periode_mulai' => '2026-07-01',
                'periode_selesai' => '2027-06-30',
                'keterangan' => 'Tahun Ajaran Aktif Berjalan',
            ],
            [
                'nama' => '2025/2026',
                'semester' => 'Genap',
                'is_active' => false,
                'periode_mulai' => '2025-07-01',
                'periode_selesai' => '2026-06-30',
                'keterangan' => 'Tahun Ajaran Sebelumnya',
            ],
            [
                'nama' => '2024/2025',
                'semester' => 'Genap',
                'is_active' => false,
                'periode_mulai' => '2024-07-01',
                'periode_selesai' => '2025-06-30',
                'keterangan' => 'Arsip Data SPMB Lama',
            ],
        ];

        foreach ($data as $item) {
            TahunAjaran::updateOrCreate(['nama' => $item['nama']], $item);
        }

        // Link existing Jalur to active Tahun Ajaran
        $activeTa = TahunAjaran::where('is_active', true)->first();
        if ($activeTa) {
            JalurPendaftaran::whereNull('tahun_ajaran_id')->update([
                'tahun_ajaran_id' => $activeTa->id,
            ]);
        }
    }
}

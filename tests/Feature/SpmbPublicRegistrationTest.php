<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pendaftaran;
use App\Models\SpmbSet;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpmbPublicRegistrationTest extends TestCase
{
    /** @test */
    public function public_can_access_spmb_registration_page()
    {
        $response = $this->get('/spmb/daftar');
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Siswa Baru');
    }

    /** @test */
    public function public_can_register_new_student_online()
    {
        $jalur = SpmbSet::first();
        if (!$jalur) {
            $jalur = SpmbSet::create([
                'nama_jalur' => 'Jalur Reguler',
                'kode_jalur' => 'REG',
                'kuota' => 100,
                'periode_buka' => now()->subDay(),
                'periode_tutup' => now()->addDays(30),
                'status' => 'aktif',
            ]);
        }

        $testNisn = '9988' . rand(100000, 999999);

        $payload = [
            'jalur_id' => $jalur->id,
            'nama_lengkap' => 'Muhammad Bintang Pratama',
            'nisn' => $testNisn,
            'nik' => '3171012304090001',
            'no_kk' => '3171012304090002',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2009-08-17',
            'agama' => 'Islam',
            'telepon' => '081299887766',
            'email' => 'bintang@gmail.com',
            'alamat' => 'Jl. Kebon Jeruk No. 10 Jakarta Barat',
            'asal_sekolah' => 'SMP Negeri 1 Jakarta',
            'npsn_asal' => '20101234',
            'nama_ayah' => 'Bambang Supriyanto',
            'pekerjaan_ayah' => 'Wiraswasta',
            'no_hp_ayah' => '081299887766',
            'nama_ibu' => 'Sri Wahyuni',
            'pekerjaan_ibu' => 'Ibu Rumah Tangga',
            'no_hp_ibu' => '081388776655',
        ];

        $response = $this->post('/spmb/daftar', $payload);

        $pendaftar = Pendaftaran::where('nisn', $testNisn)->first();
        $this->assertNotNull($pendaftar);
        $this->assertEquals('Muhammad Bintang Pratama', $pendaftar->nama_lengkap);
        $this->assertEquals('menunggu', $pendaftar->status);

        $response->assertRedirect(route('spmb.public.success', $pendaftar->id));

        // Test Success Page
        $successRes = $this->get(route('spmb.public.success', $pendaftar->id));
        $successRes->assertStatus(200);
        $successRes->assertSee($pendaftar->no_pendaftaran);
        $successRes->assertSee('Muhammad Bintang Pratama');

        // Test Status Tracking Page
        $statusRes = $this->get(route('spmb.public.status', ['search' => $testNisn]));
        $statusRes->assertStatus(200);
        $statusRes->assertSee($pendaftar->no_pendaftaran);
        $statusRes->assertSee('MENUNGGU VERIFIKASI');

        // Test Print Proof
        $proofRes = $this->get(route('spmb.public.proof', $pendaftar->id));
        $proofRes->assertStatus(200);
        $proofRes->assertSee('TANDA BUKTI PENDAFTARAN SPMB ONLINE');
        $proofRes->assertSee($pendaftar->no_pendaftaran);
    }
}

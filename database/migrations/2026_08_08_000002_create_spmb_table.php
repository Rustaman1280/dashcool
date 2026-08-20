<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spmb', function (Blueprint $table) {
            $table->id();
            $table->string('no_pendaftaran')->unique();
            $table->string('nisn')->unique();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('nik', 16)->nullable();
            $table->string('no_kk', 16)->nullable();
            $table->string('agama')->default('Islam');
            $table->text('alamat');
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('asal_sekolah');
            $table->string('npsn_asal')->nullable();
            
            // Data Orang Tua
            $table->string('nama_ayah');
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('no_hp_ayah')->nullable();
            $table->string('nama_ibu');
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('no_hp_ibu')->nullable();

            // Relasi & Status SPMB
            $table->foreignId('jalur_id')->constrained('spmb_set')->onDelete('cascade');
            $table->enum('status', ['menunggu', 'diverifikasi', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('catatan_verifikasi')->nullable();
            $table->json('dokumen')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spmb');
    }
};

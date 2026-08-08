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
        Schema::create('jalur_pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jalur');
            $table->string('kode_jalur')->unique();
            $table->integer('kuota')->default(100);
            $table->date('periode_buka');
            $table->date('periode_tutup');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'tutup'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jalur_pendaftarans');
    }
};

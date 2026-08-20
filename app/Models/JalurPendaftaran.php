<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JalurPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'spmb_set';

    protected $fillable = [
        'tahun_ajaran_id',
        'nama_jalur',
        'kode_jalur',
        'kuota',
        'periode_buka',
        'periode_tutup',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'periode_buka' => 'date',
        'periode_tutup' => 'date',
        'kuota' => 'integer',
        'tahun_ajaran_id' => 'integer',
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'jalur_id');
    }

    public function spmbs()
    {
        return $this->hasMany(Pendaftaran::class, 'jalur_id');
    }

    public function getTerisiAttribute(): int
    {
        return $this->pendaftarans()->count();
    }

    public function getPersenTerisiAttribute(): float
    {
        if ($this->kuota <= 0) return 0;
        return round(($this->terisi / $this->kuota) * 100, 1);
    }
}

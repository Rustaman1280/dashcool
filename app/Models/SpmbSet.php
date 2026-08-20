<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpmbSet extends Model
{
    use HasFactory;

    protected $table = 'spmb_set';

    protected $fillable = [
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
    ];

    public function spmbs()
    {
        return $this->hasMany(Spmb::class, 'jalur_id');
    }

    public function pendaftarans()
    {
        return $this->hasMany(Spmb::class, 'jalur_id');
    }

    public function getTerisiAttribute(): int
    {
        return $this->spmbs()->count();
    }

    public function getPersenTerisiAttribute(): float
    {
        if ($this->kuota <= 0) return 0;
        return round(($this->terisi / $this->kuota) * 100, 1);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'nama',
        'semester',
        'is_active',
        'periode_mulai',
        'periode_selesai',
        'keterangan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
    ];

    /**
     * Relasi ke Jalur Pendaftaran / SpmbSet
     */
    public function spmbSets()
    {
        return $this->hasMany(SpmbSet::class, 'tahun_ajaran_id');
    }

    /**
     * Scope untuk tahun ajaran yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

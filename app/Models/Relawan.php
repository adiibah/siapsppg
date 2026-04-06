<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Relawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_relawan',
        'nama',
        'role',
        'bagian',
        'foto',
    ];

    /**
     * Relasi ke Presensi
     * Satu relawan bisa memiliki banyak catatan presensi dalam database MySQL SIAP SPPG.
     */
    public function presensis(): HasMany
    {
        return $this->hasMany(Presensi::class);
    }

    /**
     * Relasi ke Gaji
     * Satu relawan bisa memiliki banyak catatan gaji
     */
    public function gajis(): HasMany
    {
        return $this->hasMany(Gaji::class, 'relawan_id', 'id_relawan');
    }

    /**
     * Menentukan teks yang muncul saat relawan ditemukan di pencarian global.
     */
    public function getSearchResultTitleAttribute(): string
    {
        return "{$this->id_relawan} - {$this->nama}";
    }
}
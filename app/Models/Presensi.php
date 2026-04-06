<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presensi extends Model
{
    use HasFactory;

    // Menentukan kolom mana saja yang boleh diisi secara massal
    protected $fillable = [
        'relawan_id',
        'status',
        'metode',
        'user_agent',
    ];

    /**
     * Relasi ke model Relawan
     * Satu catatan presensi dimiliki oleh satu orang relawan
     */
    public function relawan(): BelongsTo
    {
        return $this->belongsTo(Relawan::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    protected $table = 'gajis';

    protected $fillable = [
        'relawan_id',
        'bulan',
        'tahun',
        'gaji_pokok',
        'tunjangan',
        'bonus',
        'potongan',
        'gaji_bersih',
        'kehadiran',
        'nomor_rekening',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'bulan' => 'integer',
        'tahun' => 'integer',
        'gaji_pokok' => 'decimal:2',
        'tunjangan' => 'decimal:2',
        'bonus' => 'decimal:2',
        'potongan' => 'decimal:2',
        'gaji_bersih' => 'decimal:2',
        'kehadiran' => 'integer',
    ];

    /**
     * Get the relawan that owns the gaji.
     */
    public function relawan()
    {
        return $this->belongsTo(Relawan::class, 'relawan_id', 'id_relawan');
    }

    /**
     * Calculate net salary
     */
    public function calculateGajiBersih()
    {
        $total = $this->gaji_pokok + $this->tunjangan + $this->bonus - $this->potongan;
        return max(0, $total);
    }

    /**
     * Hitung gaji pokok berdasarkan kehadiran.
     */
    public function calculateGajiPokokFromAttendance()
    {
        if (!$this->relawan) {
            return 0;
        }

        // jumlah kehadiran (status 'Masuk') pada bulan/tahun yang ditentukan
        $count = $this->relawan->presensis()
            ->whereMonth('created_at', $this->bulan)
            ->whereYear('created_at', $this->tahun)
            ->where('status', 'Masuk')
            ->count();

        $this->kehadiran = $count;

        // tarif harian berdasarkan bagian
        $daily = match ($this->relawan->bagian) {
            'Delivery' => 3000000 / 26,
            'Security' => 2800000 / 26,
            'Office Boy' => 2500000 / 26,
            'Packing' => 2600000 / 26,
            'Cuci Ompreng' => 2400000 / 26,
            'Persiapan' => 2700000 / 26,
            'Produksi' => 2900000 / 26,
            'Pemorsian' => 2650000 / 26,
            default => 2500000 / 26,
        };

        return round($daily * $count, 2);
    }

    /**
     * Accessor to quickly grab attendance count stored in model or recalc.
     */
    public function getAttendanceCountAttribute()
    {
        return $this->kehadiran ?? 0;
    }

    /**
     * Generate salaries for all relawan (non-staff) for a month/year.
     */
    public static function generateForMonth(int $bulan, int $tahun)
    {
        $relawans = Relawan::where('role', '!=', 'Staff')
            ->whereIn('bagian', ['Delivery','Security','Office Boy','Packing','Cuci Ompreng','Persiapan','Produksi','Pemorsian'])
            ->get();

        foreach ($relawans as $relawan) {
            $gaji = self::firstOrNew([
                'relawan_id' => $relawan->id_relawan,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]);

            $gaji->tunjangan = 500000;
            $gaji->bonus = 0;
            $gaji->potongan = 300000;
            $gaji->status = 'draft';
            $gaji->nomor_rekening = '123456789012345' . rand(1, 9);
            $gaji->keterangan = "Gaji bulan $bulan tahun $tahun";
            $gaji->relawan()->associate($relawan);

            // hitung gaji pokok dan bersih
            $gaji->gaji_pokok = $gaji->calculateGajiPokokFromAttendance();
            $gaji->gaji_bersih = $gaji->calculateGajiBersih();

            $gaji->save();
        }
    }

    /**
     * Boot model events to recalc when saving
     */
    protected static function booted()
    {
        static::saving(function ($gaji) {
            // jika relawan, bulan, tahun sudah ada hitung otomatis
            if ($gaji->relawan && $gaji->bulan && $gaji->tahun) {
                $gaji->gaji_pokok = $gaji->calculateGajiPokokFromAttendance();
            }
            $gaji->gaji_bersih = $gaji->calculateGajiBersih();
        });
    }

    /**
     * Get formatted salary
     */
    public function getFormattedGaji()
    {
        return 'Rp' . number_format($this->gaji_bersih, 2, ',', '.');
    }

    /**
     * Scope untuk filter bulan dan tahun
     */
    public function scopeByMonth($query, $bulan, $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

    /**
     * Scope untuk filter tahun
     */
    public function scopeByYear($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }
}

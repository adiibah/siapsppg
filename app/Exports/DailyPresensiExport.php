<?php

namespace App\Exports;

use App\Models\Presensi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DailyPresensiExport implements FromQuery, WithHeadings, WithMapping
{
    protected $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    // Mengambil data presensi berdasarkan tanggal yang dipilih
    public function query()
    {
        return Presensi::query()->whereDate('created_at', $this->date);
    }

    // Menentukan judul kolom di Excel
    public function headings(): array
    {
        return ['Waktu', 'ID Relawan', 'Nama Relawan', 'Bagian', 'Status', 'Metode'];
    }

    // Memetakan data dari database ke kolom Excel
    public function map($presensi): array
    {
        return [
            $presensi->created_at->format('H:i'),
            $presensi->relawan->id_relawan,
            $presensi->relawan->nama,
            $presensi->relawan->bagian,
            $presensi->status,
            $presensi->metode,
        ];
    }
}
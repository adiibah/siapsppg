<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use Illuminate\Http\Request;

class GajiController extends Controller
{
    /**
     * Display daftar gaji for selected month and year
     */
    public function daftarGaji(Request $request)
    {
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));

        // pastikan data gaji ada untuk periode tersebut (hitung berdasarkan presensi)
        \App\Models\Gaji::generateForMonth($bulan, $tahun);

        $gajis = Gaji::with('relawan')
            ->byMonth($bulan, $tahun)
            ->whereHas('relawan', function($q) {
                $q->where('role', '!=', 'Staff');
            })
            ->get();

        $bulanArray = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $totalGaji = $gajis->sum('gaji_bersih');

        return view('gaji.daftar-gaji', compact('gajis', 'bulan', 'tahun', 'bulanArray', 'totalGaji'));
    }

    /**
     * Display slip gaji for a relawan
     */
    public function slipGaji($id, $bulan, $tahun)
    {
        $gaji = Gaji::where('relawan_id', $id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->with('relawan')
            ->firstOrFail();

        return view('gaji.slip-gaji', compact('gaji'));
    }

    /**
     * Print slip gaji
     */
    public function printSlipGaji($id, $bulan, $tahun)
    {
        $gaji = Gaji::where('relawan_id', $id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->with('relawan')
            ->firstOrFail();

        return view('gaji.print-slip-gaji', compact('gaji'));
    }
}

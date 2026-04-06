<?php

namespace App\Http\Controllers;

use App\Models\Relawan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PublicScanController extends Controller
{
    public function index()
    {
        return view('public-scan');
    }

    public function pageAbsen()
    {
        return view('page_absen');
    }

    public function process(Request $request)
    {
        $idRelawan = $request->id_relawan;
        $relawan = Relawan::where('id_relawan', $idRelawan)->first();

        if (!$relawan) {
            return response()->json(['status' => 'error', 'message' => 'ID Relawan tidak ditemukan!']);
        }

        $today = Carbon::today();
        $scanCount = Presensi::where('relawan_id', $relawan->id)
            ->whereDate('created_at', $today)
            ->count();

        if ($scanCount >= 2) {
            return response()->json(['status' => 'warning', 'message' => "{$relawan->nama} sudah absen 2x hari ini."]);
        }

        $status = ($scanCount === 0) ? 'Masuk' : 'Pulang';

        Presensi::create([
            'relawan_id' => $relawan->id,
            'status' => $status,
            'metode' => 'Public-Scanner',
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'status' => 'success', 
            'message' => "Berhasil $status!",
            'nama' => $relawan->nama
        ]);
    }
}
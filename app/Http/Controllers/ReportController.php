<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Menangani rute laporan harian PDF.
     * Saat ini hanya menampilkan pesan sementara jika rute diakses.
     */
    public function generateDailyReport(Request $request)
    {
        return response('Laporan harian belum diimplementasikan sepenuhnya.', 200);
    }
}

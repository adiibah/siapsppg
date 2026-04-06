<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicScanController;
use App\Http\Controllers\RelawanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GajiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Mengarahkan halaman utama langsung ke Panel Admin
Route::get('/', function () {
    return redirect('/admin');
});

// 2. --- FITUR SCANNER (PUBLIC) ---
// Bisa diakses relawan di lapangan tanpa perlu login ke sistem admin
Route::get('/scan', [PublicScanController::class, 'index'])->name('public.scan');
Route::get('/page_absen', [PublicScanController::class, 'pageAbsen'])->name('public.page_absen');
Route::post('/scan/process', [PublicScanController::class, 'process'])->name('public.scan.process');


// 3. --- FITUR ADMIN (MENGGUNAKAN AUTH) ---
// Hanya admin yang sudah login yang bisa mengakses rute di bawah ini
Route::middleware(['auth'])->group(function () {
    
    // Rute untuk menampilkan Digital ID Card Relawan (web version)
    Route::get('/admin/relawan/{relawan}/digital-id-card', [RelawanController::class, 'displayDigitalIdCard'])
        ->name('relawan.digital-id-card');

    // Rute untuk men-generate Laporan Harian PDF
    Route::get('/admin/laporan/harian-pdf', [ReportController::class, 'generateDailyReport'])
        ->name('laporan.harian.pdf');

    // Rute untuk Daftar Gaji dan Slip Gaji
    Route::get('/gaji/daftar-gaji', [GajiController::class, 'daftarGaji'])
        ->name('gaji.daftar-gaji');
    
    Route::get('/gaji/slip-gaji/{id}/{bulan}/{tahun}', [GajiController::class, 'slipGaji'])
        ->name('gaji.slip-gaji');
    
    Route::get('/gaji/print-slip-gaji/{id}/{bulan}/{tahun}', [GajiController::class, 'printSlipGaji'])
        ->name('gaji.print-slip-gaji');

});
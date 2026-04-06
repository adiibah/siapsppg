<?php

namespace App\Http\Controllers;

use App\Models\Relawan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RelawanController extends Controller
{
    /**
     * Menampilkan Digital ID Card Relawan
     */
    public function displayDigitalIdCard(Relawan $relawan)
    {
        return view('digital-id-card', [
            'relawan' => $relawan,
            'qrCode' => $this->generateQrCodeSvg($relawan->id_relawan),
        ]);
    }

    /**
     * Menggenerate ID Card Relawan dalam bentuk PDF
     */
    public function downloadIdCard(Relawan $relawan)
    {
        // Generate QR Code sebagai SVG Data URI
        $qrCodeSvg = $this->generateQrCodeSvg($relawan->id_relawan);

        // Data yang akan dikirim ke tampilan PDF
        $data = [
            'relawan' => $relawan,
            'title'   => 'ID Card Relawan ' . $relawan->nama,
            'date'    => date('d/m/Y'),
            'qrCode'  => $qrCodeSvg,
        ];

        // Memanggil view blade yang berisi desain kartu
        // Ukuran [0, 0, 153.07, 243.78] adalah konversi mm ke point untuk 54mm x 86mm (Portrait)
        $pdf = Pdf::loadView('id-card-pdf', $data)
                ->setPaper([0, 0, 153.07, 243.78], 'portrait');

        // Menampilkan PDF di tab baru
        return $pdf->download('ID_Card_' . $relawan->id_relawan . '.pdf');
    }

    /**
     * Generate QR Code sebagai Data URI SVG
     */
    private function generateQrCodeSvg($text)
    {
        // Menggunakan API eksternal yang mengembalikan PNG untuk dikonversi
        // Alternatif: gunakan URL dengan parameter untuk mendapatkan base64
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($text);
        
        // Download image dan convert ke base64
        $imageData = @file_get_contents($qrUrl);
        if ($imageData === false) {
            // Fallback jika gagal download, buat QR kosong
            return '';
        }
        
        return 'data:image/png;base64,' . base64_encode($imageData);
    }
}
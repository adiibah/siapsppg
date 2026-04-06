<?php

namespace Database\Seeders;

use App\Models\Gaji;
use App\Models\Relawan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GajiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hanya relawan operasional, bukan staff administratif
        $relawans = Relawan::where('role', '!=', 'Staff')
            ->whereIn('bagian', ['Delivery', 'Security', 'Office Boy', 'Packing', 'Cuci Ompreng', 'Persiapan', 'Produksi', 'Pemorsian'])
            ->get();

        $bulanSekarang = date('n');
        $tahunSekarang = date('Y');

        foreach ($relawans as $relawan) {
            // Gaji dasar berdasarkan bagian
            // hitung kehadiran (status Masuk) di bulan/tahun ini
            $kehadiran = $relawan->presensis()
                ->whereMonth('created_at', $bulanSekarang)
                ->whereYear('created_at', $tahunSekarang)
                ->where('status', 'Masuk')
                ->count();

            // tarif harian berdasarkan bagian
            $daily = match($relawan->bagian) {
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

            $gajiPokok = round($daily * $kehadiran, 2);
            $tunjangan = 500000;
            $bonus = 0;
            $potongan = 300000;
            $gajiBersih = $gajiPokok + $tunjangan + $bonus - $potongan;

            Gaji::updateOrCreate(
                [
                    'relawan_id' => $relawan->id_relawan,
                    'bulan' => $bulanSekarang,
                    'tahun' => $tahunSekarang,
                ],
                [
                    'gaji_pokok' => $gajiPokok,
                    'tunjangan' => $tunjangan,
                    'bonus' => $bonus,
                    'potongan' => $potongan,
                    'gaji_bersih' => $gajiBersih,
                    'kehadiran' => $kehadiran,
                    'nomor_rekening' => '123456789012345' . random_int(1, 9),
                    'status' => 'draft',
                    'keterangan' => 'Gaji bulan ' . $bulanSekarang . ' tahun ' . $tahunSekarang,
                ]
            );
        }
    }
}

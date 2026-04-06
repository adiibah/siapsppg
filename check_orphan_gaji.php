<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Gaji;

$count = Gaji::doesntHave('relawan')->count();
echo "orphans:$count\n";

// regenerate for this month/year to test attendance
App\Models\Gaji::generateForMonth(date('n'), date('Y'));

// show first record
if (App\Models\Gaji::count()) {
    $g = App\Models\Gaji::first();
    echo "after gen sample: relawan=" . ($g->relawan?->id_relawan ?? 'n/a') . " bulan={$g->bulan} tahun={$g->tahun} kehadiran={$g->attendance_count} pokok=" . $g->gaji_pokok . "\n";
}

// print first gaji sample
if ($countRecords = Gaji::count()) {
    $g = Gaji::first();
    echo "sample: relawan=" . ($g->relawan?->id_relawan ?? 'n/a') . " bulan={$g->bulan} tahun={$g->tahun} kehadiran={$g->attendance_count} pokok=" . $g->gaji_pokok . "\n";
}

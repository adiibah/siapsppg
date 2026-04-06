<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// delete only staff entries from gajis
DB::statement("DELETE g FROM gajis g JOIN relawans r ON g.relawan_id = r.id_relawan WHERE r.role = 'Staff'");
echo "Staff-related gaji records deleted\n";

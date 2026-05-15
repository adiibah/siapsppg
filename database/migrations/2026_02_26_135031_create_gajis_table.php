<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gajis', function (Blueprint $table) {
            $table->id();
            $table->string('relawan_id');
            $table->foreign('relawan_id')
                ->references('id_relawan')
                ->on('relawans')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->integer('bulan'); // 1-12
            $table->year('tahun');
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('tunjangan', 15, 2)->default(0);
            $table->decimal('bonus', 15, 2)->default(0);
            $table->decimal('potongan', 15, 2)->default(0);
            $table->decimal('gaji_bersih', 15, 2)->default(0);
            $table->string('nomor_rekening')->nullable();
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->unique(['relawan_id', 'bulan', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gajis');
    }
};

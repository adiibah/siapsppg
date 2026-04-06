<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk tabel presensi.
     */
    public function up(): void
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel relawans dengan proteksi penghapusan otomatis
            $table->foreignId('relawan_id')
                  ->constrained('relawans')
                  ->cascadeOnDelete();
            
            // Status: Masuk, Pulang, Sakit, Izin, Alpha
            // Menambahkan index agar filter status di dashboard lebih cepat
            $table->string('status')->index(); 
            
            // Metode penginputan data
            $table->string('metode')->default('Instant-Scanner');
            
            // Informasi perangkat (User Agent)
            $table->text('user_agent')->nullable();
            
            // Mencatat waktu absen (created_at) dan waktu edit (updated_at)
            $table->timestamps(); 
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
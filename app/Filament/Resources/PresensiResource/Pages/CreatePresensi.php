<?php

namespace App\Filament\Resources\RelawanResource\Pages;

use App\Filament\Resources\RelawanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRelawan extends CreateRecord
{
    protected static string $resource = RelawanResource::class;

    /**
     * Mengarahkan admin kembali ke halaman daftar relawan setelah berhasil menyimpan.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Memberikan notifikasi sukses yang lebih spesifik.
     */
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data Relawan baru berhasil didaftarkan ke sistem!';
    }
}
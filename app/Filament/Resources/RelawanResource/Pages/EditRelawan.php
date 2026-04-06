<?php

namespace App\Filament\Resources\RelawanResource\Pages;

use App\Filament\Resources\RelawanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRelawan extends EditRecord
{
    protected static string $resource = RelawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tetap pertahankan tombol hapus di pojok kanan atas halaman edit
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Setelah menekan tombol 'Save changes', admin akan otomatis 
     * diarahkan kembali ke tabel utama Data Relawan.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Pesan sukses saat data berhasil diperbarui.
     */
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Perubahan data relawan telah berhasil disimpan!';
    }
}
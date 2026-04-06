<?php

namespace App\Filament\Resources\PresensiResource\Pages;

use App\Filament\Resources\PresensiResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPresensi extends EditRecord
{
    protected static string $resource = PresensiResource::class;

    /**
     * Menambahkan tombol aksi di bagian atas halaman edit (Header).
     */
    protected function getHeaderActions(): array
    {
        return [
            // Menampilkan tombol hapus di pojok kanan atas halaman edit
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Mengarahkan kembali ke halaman daftar (Index) setelah berhasil menyimpan perubahan.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Menampilkan notifikasi sukses yang telah dikustomisasi.
     */
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data Diperbarui')
            ->body('Perubahan pada data presensi berhasil disimpan ke sistem.');
    }
}
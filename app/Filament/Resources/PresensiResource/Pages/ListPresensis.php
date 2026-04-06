<?php

namespace App\Filament\Resources\PresensiResource\Pages;

use App\Filament\Resources\PresensiResource;
use App\Models\Presensi;
use App\Exports\DailyPresensiExport; 
use Maatwebsite\Excel\Facades\Excel; 
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPresensis extends ListRecords
{
    protected static string $resource = PresensiResource::class;

    /**
     * Tombol aksi di bagian atas tabel.
     */
    protected function getHeaderActions(): array
    {
        return [
            // Fitur Laporan Harian ke Excel menggunakan maatwebsite/excel
            Action::make('exportLaporan')
                ->label('Laporan Harian')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->form([
                    DatePicker::make('tanggal')
                        ->label('Pilih Tanggal Laporan')
                        ->default(now())
                        ->required(),
                ])
                ->action(function (array $data) {
                    return Excel::download(
                        new DailyPresensiExport($data['tanggal']),
                        "Laporan_Presensi_{$data['tanggal']}.xlsx"
                    );
                }),

            Actions\CreateAction::make()
                ->label('Input Presensi Baru')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    /**
     * Fitur TABS untuk filter cepat di atas tabel.
     */
    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua Riwayat'),
            
            'hari_ini' => Tab::make('Hari Ini')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('created_at', now()))
                ->badge(Presensi::whereDate('created_at', now())->count())
                ->badgeColor('success'),

            'sakit_izin' => Tab::make('Sakit & Izin')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['Sakit', 'Izin']))
                ->icon('heroicon-m-hand-raised')
                ->badge(Presensi::whereIn('status', ['Sakit', 'Izin'])->whereDate('created_at', now())->count())
                ->badgeColor('warning'),
        ];
    }

    /**
     * Memanggil Widget Statistik di atas tabel.
     */
    protected function getHeaderWidgets(): array
    {
        return [
            // PENTING: Gunakan namespace global agar tidak bentrok (setelah menghapus file duplikat)
            // Use string class name to avoid static-analysis warning if the widget class is not present.
            'App\\Filament\\Widgets\\PresentStatsOverview',
        ];
    }
}
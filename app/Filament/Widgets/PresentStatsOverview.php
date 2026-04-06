<?php

namespace App\Filament\Widgets;

use App\Models\Presensi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class PresentStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::now()->toDateString();
        
        // Total Presensi Hari Ini
        $totalPresisi = Presensi::whereDate('created_at', $today)->count();
        
        // Masuk Hari Ini
        $countMasuk = Presensi::whereDate('created_at', $today)
            ->where('status', 'Masuk')
            ->count();
        
        // Pulang Hari Ini
        $countPulang = Presensi::whereDate('created_at', $today)
            ->where('status', 'Pulang')
            ->count();
        
        // Sakit & Izin Hari Ini
        $countSakitIzin = Presensi::whereDate('created_at', $today)
            ->whereIn('status', ['Sakit', 'Izin'])
            ->count();
        
        // Alpha Hari Ini
        $countAlpha = Presensi::whereDate('created_at', $today)
            ->where('status', 'Alpha')
            ->count();

        return [
            Stat::make('Total Presensi', $totalPresisi)
                ->description('Hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
            
            Stat::make('Masuk', $countMasuk)
                ->description('Status Masuk')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            
            Stat::make('Pulang', $countPulang)
                ->description('Status Pulang')
                ->descriptionIcon('heroicon-m-arrow-left-end-on-rectangle')
                ->color('danger'),
            
            Stat::make('Sakit/Izin', $countSakitIzin)
                ->description('Status lainnya')
                ->descriptionIcon('heroicon-m-hand-raised')
                ->color('warning'),
            
            Stat::make('Alpha', $countAlpha)
                ->description('Tidak hadir')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('gray'),
        ];
    }
}

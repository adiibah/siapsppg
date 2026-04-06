<?php

namespace App\Filament\Resources\GajiResource\Pages;

use App\Filament\Resources\GajiResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListGajis extends ListRecords
{
    protected static string $resource = GajiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('generate')
                ->label('Generate Gaji')
                ->form([
                    Forms\Components\Select::make('bulan')
                        ->label('Bulan')
                        ->options([
                            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                            7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('tahun')->label('Tahun')->numeric()->required()->default(date('Y')),
                ])
                ->action(function (array $data) {
                    \App\Models\Gaji::generateForMonth($data['bulan'], $data['tahun']);
                    Notification::make()
                        ->title("Gaji untuk bulan {$data['bulan']} tahun {$data['tahun']} telah digenerate.")
                        ->success()
                        ->send();
                })
                ->modalHeading('Generate Gaji Periode')
                ->modalButton('Hitung'),
        ];
    }
}

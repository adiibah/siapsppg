<?php

namespace App\Filament\Resources\RelawanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PresensisRelationManager extends RelationManager
{
    protected static string $relationship = 'presensis';
    public function table(Table $table): Table
{
    return $table
        ->recordTitleAttribute('status')
        ->columns([
            Tables\Columns\TextColumn::make('created_at')
                ->label('Waktu')
                ->dateTime('d/m/Y H:i')
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Masuk' => 'success',
                    'Pulang' => 'info',
                    'Sakit', 'Izin' => 'warning',
                    default => 'gray',
                }),
            Tables\Columns\TextColumn::make('metode'),
        ])
        ->filters([
            //
        ])
        ->headerActions([
            // Memungkinkan Admin tambah absen manual dari sini
            Tables\Actions\CreateAction::make(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
}
}
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PresensiResource\Pages;
use App\Models\Presensi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PresensiResource extends Resource
{
    protected static ?string $model = Presensi::class;

    // Icon navigasi (centang)
    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    
    protected static ?string $navigationLabel = 'Log Presensi';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Input Presensi Manual')
                ->description('Gunakan ini jika relawan lupa scan atau data susulan.')
                ->schema([
                    Forms\Components\Select::make('relawan_id')
                        ->label('Pilih Relawan')
                        ->relationship('relawan', 'nama')
                        ->searchable()
                        ->preload()
                        ->required(),
                    
                    // Tambahkan Input Waktu Manual di sini
                    Forms\Components\DateTimePicker::make('created_at')
                        ->label('Waktu Presensi')
                        ->default(now()) // Defaultnya adalah waktu sekarang
                        ->required()
                        ->displayFormat('d/m/Y H:i')
                        ->seconds(false), // Menit saja cukup

                    Forms\Components\Select::make('status')
                        ->options([
                            'Masuk' => 'Masuk',
                            'Pulang' => 'Pulang',
                            'Sakit' => 'Sakit',
                            'Izin' => 'Izin',
                            'Alpha' => 'Alpha',
                        ])
                        ->helperText('Kosongkan jika ingin sistem menentukan otomatis (Masuk/Pulang).')
                        ->placeholder('Pilih Status (Opsional)'),

                    Forms\Components\Hidden::make('metode')
                        ->default('Admin-Manual'),
                ])->columns(2),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('H:i') // Hanya menampilkan jam:menit
                    ->sortable(),

                Tables\Columns\TextColumn::make('relawan.nama')
                    ->label('Nama Relawan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('relawan.bagian')
                    ->label('Bagian')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('status')
                    ->badge() // Membuat tampilan berwarna
                    ->color(fn (string $state): string => match ($state) {
                        'Masuk' => 'success',
                        'Pulang' => 'danger',
                        'Sakit', 'Izin' => 'warning',
                        'Alpha' => 'gray',
                        default => 'primary',
                    }),

                Tables\Columns\TextColumn::make('metode')
                    ->label('Metode')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc') // Data terbaru di atas
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Masuk' => 'Masuk',
                        'Pulang' => 'Pulang',
                        'Sakit' => 'Sakit',
                        'Izin' => 'Izin',
                    ]),
                // Filter untuk melihat absen hari ini saja
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal_absen')
                            ->default(now()),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => 
                        $query->when($data['tanggal_absen'], fn ($q) => $q->whereDate('created_at', $data['tanggal_absen']))
                    )
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPresensis::route('/'),
            // 'create' page removed: corresponding Page class not present in Pages namespace
            'edit' => Pages\EditPresensi::route('/{record}/edit'),
        ];
    }
}
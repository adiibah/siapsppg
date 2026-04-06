<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RelawanResource\Pages;
use App\Models\Relawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter; // Tambahkan ini

class RelawanResource extends Resource
{
    protected static ?string $model = Relawan::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Data Relawan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Relawan')
                    ->description('Masukkan detail data relawan di bawah ini.')
                    ->schema([
                        Forms\Components\TextInput::make('id_relawan')
                            ->label('ID Relawan')
                            ->placeholder('Contoh: SPPG-001')
                            ->required()
                            ->unique(ignoreRecord: true),
                        
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->required(),
                        
                        Forms\Components\TextInput::make('role')
                            ->label('Jabatan / Role')
                            ->placeholder('Contoh: Staff, Koordinator')
                            ->required(),
                        
                        Forms\Components\TextInput::make('bagian')
                            ->label('Bagian / Unit')
                            ->placeholder('Contoh: Delivery, Security, Office Boy, Packing')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Prepare bulk actions and guard against missing ExportBulkAction class
        $deleteBulk = Tables\Actions\DeleteBulkAction::make();
        $bulkActionsArray = [$deleteBulk];
        if (class_exists(\pxl\FilamentExcel\Actions\Tables\ExportBulkAction::class)) {
            $bulkActionsArray[] = \pxl\FilamentExcel\Actions\Tables\ExportBulkAction::make()
                ->label('Export ke Excel');
        }

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_relawan')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Relawan')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('role')
                    ->label('Jabatan')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('bagian')
                    ->label('Bagian'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar Pada')
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // FITUR BARU: Filter per bagian
                SelectFilter::make('bagian')
                    ->options([
                        'Delivery' => 'Delivery',
                        'Security' => 'Security',
                        'Office Boy' => 'Office Boy',
                        'Packing' => 'Packing',
                        'Cuci Ompreng' => 'Cuci Ompreng',
                        'Persiapan' => 'Persiapan',
                        'Produksi' => 'Produksi',
                        'Pemorsian' => 'Pemorsian',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                // FITUR BARU: Tombol ID Card (buka halaman Digital ID untuk unduh PNG)
                Tables\Actions\Action::make('download_id_card')
                    ->label('ID Card')
                    ->icon('heroicon-o-identification')
                    ->color('success')
                    ->url(fn (Relawan $record): string => route('relawan.digital-id-card', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make($bulkActionsArray),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRelawans::route('/'),
            'create' => Pages\CreateRelawan::route('/create'),
            'edit' => Pages\EditRelawan::route('/{record}/edit'),
        ];
    }
}
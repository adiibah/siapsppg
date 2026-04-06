<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GajiResource\Pages;
use App\Models\Gaji;
use App\Models\Relawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class GajiResource extends Resource
{
    protected static ?string $model = Gaji::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Manajemen Gaji';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('relawan_id')
                    ->label('Relawan')
                    ->options(fn() => Relawan::where('role', '!=', 'Staff')
                        ->whereIn('bagian', ['Delivery','Security','Office Boy','Packing','Cuci Ompreng','Persiapan','Produksi','Pemorsian'])
                        ->orderBy('nama')
                        ->pluck('nama', 'id_relawan')
                        ->toArray()
                    )
                    ->searchable()
                    ->required(),

                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Select::make('bulan')
                            ->label('Bulan')
                            ->options([
                                1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                                7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('tahun')
                            ->label('Tahun')
                            ->numeric()
                            ->required()
                            ->default(date('Y')),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(['draft'=>'Draft','approved'=>'Approved','paid'=>'Paid'])
                            ->default('draft')
                            ->required(),
                    ]),

                Forms\Components\Section::make('Komponen Gaji')
                    ->schema([
                        Forms\Components\TextInput::make('gaji_pokok')->label('Gaji Pokok')->numeric()->required()->default(0)
                            ->disabled(),
                        Forms\Components\TextInput::make('tunjangan')->label('Tunjangan')->numeric()->default(0),
                        Forms\Components\TextInput::make('bonus')->label('Bonus')->numeric()->default(0),
                        Forms\Components\TextInput::make('potongan')->label('Potongan')->numeric()->default(0),
                        Forms\Components\TextInput::make('kehadiran')->label('Kehadiran')->numeric()->disabled(),
                        Forms\Components\TextInput::make('gaji_bersih')->label('Gaji Bersih')->numeric()->disabled(),
                    ]),

                Forms\Components\TextInput::make('nomor_rekening')->label('Nomor Rekening')->nullable(),
                Forms\Components\Textarea::make('keterangan')->label('Keterangan')->rows(3)->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn() => Gaji::whereHas('relawan', fn($q)=> $q->where('role','!=','Staff')))
            ->columns([
                TextColumn::make('relawan_id')->label('ID')
                    ->getStateUsing(fn($record) => $record->relawan?->id_relawan ?? $record->relawan_id),

                TextColumn::make('relawan_nama')->label('Nama Relawan')
                    ->getStateUsing(fn($record) => $record->relawan?->nama ?? '-'),

                TextColumn::make('relawan_bagian')->label('Bagian')
                    ->getStateUsing(fn($record) => $record->relawan?->bagian ?? '-'),

                TextColumn::make('bulan')->label('Bulan')->sortable()->formatStateUsing(fn($state)=>[
                    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
                ][$state] ?? 'N/A'),

                TextColumn::make('tahun')->label('Tahun')->sortable(),

                TextColumn::make('nomor_rekening')->label('No. Rekening')->sortable(),

                TextColumn::make('gaji_bersih')->label('Nominal Gaji')->money('IDR')->sortable(),

                BadgeColumn::make('status')->label('Status')->colors(['secondary'=>'draft','info'=>'approved','success'=>'paid'])->sortable(),
            ])
            ->filters([
                SelectFilter::make('bulan')->label('Bulan')->options([
                    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                    7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
                ]),

                SelectFilter::make('tahun')->label('Tahun')->options(fn()=>Gaji::distinct()->pluck('tahun')->mapWithKeys(fn($y)=>[$y=>$y])->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print-slip')
                    ->label('Cetak Slip')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('gaji.slip-gaji', [$record->relawan_id, $record->bulan, $record->tahun]))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGajis::route('/'),
            'create' => Pages\CreateGaji::route('/create'),
            'edit' => Pages\EditGaji::route('/{record}/edit'),
        ];
    }
}

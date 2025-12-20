<?php

namespace App\Filament\Resources\Bookings;

use App\Models\Booking;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextInputColumn;
use App\Filament\Resources\Bookings\Pages;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use BackedEnum;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-wrench';
    protected static ?string $navigationLabel = 'Booking';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama User')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('mechanic.name')
                    ->label('Mekanik')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('date')
                    ->label('Tanggal')
                    ->formatStateUsing(
                        fn ($state) => $state ? Carbon::parse($state)->format('d M Y') : '-'
                    )
                    ->sortable(),

                TextColumn::make('time')
                    ->label('Jam'),

                TextColumn::make('notes')
                    ->label('Keluhan')
                    ->limit(35)
                    ->wrap()
                    ->toggleable(),

                TextInputColumn::make('estimation')
                    ->label('Estimasi')
                    ->placeholder('Isi estimasi')
                    ->updateStateUsing(function (Booking $record, $state) {
                        $record->update(['estimation' => $state]);

                        Notification::make()
                            ->title('Estimasi otomatis disimpan!')
                            ->success()
                            ->send();
                    }),

                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'menunggu'    => 'Menunggu',
                        'proses'     => 'Proses',
                        'selesai'    => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ])
                    ->updateStateUsing(function (Booking $record, $state) {
                        $record->update(['status' => $state]);

                        Notification::make()
                            ->title('Status otomatis disimpan!')
                            ->success()
                            ->send();
                    }),

                TextColumn::make('booking_code')
                    ->label('Kode')
                    ->copyable()
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
        ];
    }
}
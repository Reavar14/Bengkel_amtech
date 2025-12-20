<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{
    TextInput,
    Select,
    DatePicker,
    TimePicker,
    Textarea
};

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([ // ⬅️ WAJIB schema(), BUKAN components()
            TextInput::make('user_id')
                ->numeric()
                ->disabled() // admin lihat saja
                ->dehydrated(),

            TextInput::make('name')
                ->label('Nama User')
                ->disabled()
                ->dehydrated(),

            TextInput::make('phone')
                ->label('No. HP')
                ->disabled()
                ->dehydrated(),

            TextInput::make('email')
                ->disabled()
                ->dehydrated(),

            Select::make('mechanic_id')
                ->label('Mekanik')
                ->relationship('mechanic', 'name') // ✅ SUDAH BENAR
                ->searchable()
                ->preload()
                ->required(),

            DatePicker::make('date')
                ->disabled()
                ->dehydrated(),

            TimePicker::make('time')
                ->disabled()
                ->dehydrated(),

            Textarea::make('notes')
                ->label('Keluhan')
                ->columnSpanFull()
                ->disabled()
                ->dehydrated(),

            TextInput::make('estimation')
                ->label('Estimasi (jam)')
                ->numeric(),

            Select::make('status')
                ->options([
                    'pending'    => 'Menunggu',
                    'proses'     => 'Diproses',
                    'selesai'    => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                ])
                ->required(),

            TextInput::make('estimated_finish')
                ->label('Perkiraan Selesai')
                ->datetime(),
        ]);
    }
}
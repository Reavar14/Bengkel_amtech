<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BookingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('name'),
                TextEntry::make('phone'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('mechanic')
                    ->placeholder('-'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('time')
                    ->time(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('estimation')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('estimated_finish')
                    ->placeholder('-'),
                TextEntry::make('mechanic_id')
                    ->numeric()
                    ->placeholder('-'),
            ]);
    }
}

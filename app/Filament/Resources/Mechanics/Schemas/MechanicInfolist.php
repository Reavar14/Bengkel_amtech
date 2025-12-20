<?php

namespace App\Filament\Resources\Mechanics\Schemas;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;

class MechanicInfolist
{
    public static function configure(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')
                    ->label('Nama Mekanik')
                    ->placeholder('-'),

                TextEntry::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label('Diubah')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
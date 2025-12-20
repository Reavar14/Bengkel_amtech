<?php

namespace App\Filament\Resources\Mechanics\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class MechanicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Nama Mekanik')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
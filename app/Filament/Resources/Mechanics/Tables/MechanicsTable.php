<?php

namespace App\Filament\Resources\Mechanics\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class MechanicsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Mekanik')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime(),

                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime(),
            ]);
    }
}
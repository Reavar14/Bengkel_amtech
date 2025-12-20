<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([

            TextColumn::make('user_id')
                ->sortable(),

            TextColumn::make('name')
                ->searchable(),

            TextColumn::make('phone')
                ->searchable(),

            TextColumn::make('email')
                ->searchable(),

            // ⬇️ INI YANG BENAR UNTUK MEKANIK
            TextColumn::make('mechanic.name')
                ->label('Mekanik')
                ->sortable()
                ->searchable(),

            TextColumn::make('date')
                ->date()
                ->sortable(),

            TextColumn::make('time')
                ->time()
                ->sortable(),

            TextColumn::make('estimation'),

            TextColumn::make('status'),

            TextColumn::make('estimated_finish'),

            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

        ])
        ->recordActions([
            ViewAction::make(),
            EditAction::make(),
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
    }
}
<?php

namespace App\Filament\Resources\Mechanics;

use App\Models\Mechanic;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Mechanics\Pages;
use Illuminate\Database\Eloquent\Model;
use BackedEnum;

class MechanicResource extends Resource
{
    protected static ?string $model = Mechanic::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-wrench';
    protected static ?string $navigationLabel = 'Mekanik';

    /* ================= FORM ================= */
    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label('Nama Mekanik')
                ->required()
                ->maxLength(100),
        ]);
    }

    /* ================= TABLE ================= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Mekanik')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime(),
            ])

            // ✅ INI SATU-SATUNYA CARA BENAR DI FILAMENT v4
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ]);
    }

    /* ================= PAGES ================= */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMechanics::route('/'),
            'create' => Pages\CreateMechanic::route('/create'),
            'edit'   => Pages\EditMechanic::route('/{record}/edit'),
        ];
    }

    /* ================= AUTH ================= */
    public static function canViewAny(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->role === 'admin';
    }
}
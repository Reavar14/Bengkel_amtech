<?php

namespace App\Filament\Resources\Mechanics\Pages;

use App\Filament\Resources\Mechanics\MechanicResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListMechanics extends ListRecords
{
    protected static string $resource = MechanicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
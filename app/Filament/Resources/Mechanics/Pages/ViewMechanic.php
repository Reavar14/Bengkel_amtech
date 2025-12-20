<?php

namespace App\Filament\Resources\Mechanics\Pages;

use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Mechanics\MechanicResource;
use App\Filament\Resources\Mechanics\Schemas\MechanicInfolist;

class ViewMechanic extends ViewRecord
{
    protected static string $resource = MechanicResource::class;

    // ubah menjadi PUBLIC
    public function getTitle(): string
    {
        return 'Detail Mekanik';
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getInfolist(): array
    {
        return [
            MechanicInfolist::class,
        ];
    }
}
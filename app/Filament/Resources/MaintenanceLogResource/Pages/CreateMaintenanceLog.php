<?php

namespace App\Filament\Resources\MaintenanceLogResource\Pages;

use App\Filament\Resources\MaintenanceLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMaintenanceLog extends CreateRecord
{
    protected static string $resource = MaintenanceLogResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

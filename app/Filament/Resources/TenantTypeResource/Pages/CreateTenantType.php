<?php

namespace App\Filament\Resources\TenantTypeResource\Pages;

use App\Filament\Resources\TenantTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenantType extends CreateRecord
{
    protected static string $resource = TenantTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

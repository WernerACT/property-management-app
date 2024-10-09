<?php

namespace App\Filament\Resources\VendorTypeResource\Pages;

use App\Filament\Resources\VendorTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorType extends CreateRecord
{
    protected static string $resource = VendorTypeResource::class;

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

<?php

namespace App\Filament\Resources\LeaseResource\Pages;

use App\Filament\Resources\LeaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLease extends CreateRecord
{
    protected static string $resource = LeaseResource::class;

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

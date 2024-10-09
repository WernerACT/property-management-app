<?php

namespace App\Filament\Resources\PropertyImageResource\Pages;

use App\Filament\Resources\PropertyImageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePropertyImage extends CreateRecord
{
    protected static string $resource = PropertyImageResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

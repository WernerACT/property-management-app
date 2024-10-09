<?php

namespace App\Filament\Resources\PropertyDescriptionResource\Pages;

use App\Filament\Resources\PropertyDescriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePropertyDescription extends CreateRecord
{
    protected static string $resource = PropertyDescriptionResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

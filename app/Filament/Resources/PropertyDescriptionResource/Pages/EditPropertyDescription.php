<?php

namespace App\Filament\Resources\PropertyDescriptionResource\Pages;

use App\Filament\Resources\PropertyDescriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyDescription extends EditRecord
{
    protected static string $resource = PropertyDescriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

<?php

namespace App\Filament\Resources\ManagingAgentTypeResource\Pages;

use App\Filament\Resources\ManagingAgentTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateManagingAgentType extends CreateRecord
{
    protected static string $resource = ManagingAgentTypeResource::class;

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

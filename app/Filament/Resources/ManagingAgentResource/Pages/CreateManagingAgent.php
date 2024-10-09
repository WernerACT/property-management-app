<?php

namespace App\Filament\Resources\ManagingAgentResource\Pages;

use App\Filament\Resources\ManagingAgentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateManagingAgent extends CreateRecord
{
    protected static string $resource = ManagingAgentResource::class;

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

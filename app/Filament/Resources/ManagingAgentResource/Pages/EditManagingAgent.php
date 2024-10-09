<?php

namespace App\Filament\Resources\ManagingAgentResource\Pages;

use App\Filament\Resources\ManagingAgentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditManagingAgent extends EditRecord
{
    protected static string $resource = ManagingAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

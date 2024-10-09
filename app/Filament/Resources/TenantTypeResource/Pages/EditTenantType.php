<?php

namespace App\Filament\Resources\TenantTypeResource\Pages;

use App\Filament\Resources\TenantTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTenantType extends EditRecord
{
    protected static string $resource = TenantTypeResource::class;

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

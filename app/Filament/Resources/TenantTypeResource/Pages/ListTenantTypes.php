<?php

namespace App\Filament\Resources\TenantTypeResource\Pages;

use App\Filament\Resources\TenantTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTenantTypes extends ListRecords
{
    protected static string $resource = TenantTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

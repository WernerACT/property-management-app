<?php

namespace App\Filament\Resources\ManagingAgentResource\Pages;

use App\Filament\Resources\ManagingAgentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListManagingAgents extends ListRecords
{
    protected static string $resource = ManagingAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

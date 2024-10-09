<?php

namespace App\Filament\Resources\PropertyDescriptionResource\Pages;

use App\Filament\Resources\PropertyDescriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertyDescriptions extends ListRecords
{
    protected static string $resource = PropertyDescriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

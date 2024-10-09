<?php

namespace App\Filament\Resources\RentalScheduleResource\Pages;

use App\Filament\Resources\RentalScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRentalSchedules extends ListRecords
{
    protected static string $resource = RentalScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

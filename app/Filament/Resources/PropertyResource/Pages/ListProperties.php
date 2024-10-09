<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use App\Filament\Resources\PropertyResource;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ListRecords;


class ListProperties extends ListRecords
{
    use HasFiltersForm;

    use ExposesTableToWidgets;

    protected static string $resource = PropertyResource::class;

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('start_date'),
            DatePicker::make('end_date'),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PropertyResource\Widgets\PropertyOverview::class,
        ];
    }
}

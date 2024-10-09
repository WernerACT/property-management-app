<?php

namespace App\Filament\Resources\EntityResource\Widgets;

use App\Filament\Resources\EntityResource\Pages\ListEntities;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EntityOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListEntities::class;
    }

    protected function getStats(): array
    {
        $records = $this->getPageTableRecords();

        $totalPortfolioValue = $records->sum(function ($entity) {
            return $entity->properties->sum('current_value');
        });

        return [
            Stat::make('Total Portfolio Value', number_format($totalPortfolioValue, 2) . ' ZAR')
                ->description('Sum of all property values')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),
        ];
    }
}

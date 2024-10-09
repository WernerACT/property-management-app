<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTransactions extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All transactions'),
            'income' => Tab::make('Only Income')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('transaction_type', '=', 'income')),
            'expense' => Tab::make('Only Expenses')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('transaction_type', '=', 'expense')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TransactionResource\Widgets\TransactionOverview::class,
        ];
    }
}

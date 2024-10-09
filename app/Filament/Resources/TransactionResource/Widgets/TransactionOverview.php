<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Filament\Resources\TransactionResource\Pages\ListTransactions;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransactionOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListTransactions::class;
    }

    protected function getStats(): array
    {
        $records = $this->getPageTableRecords();

        $totalIncome = $records->filter(function ($transaction) {
            return $transaction->transaction_type === 'income' && $transaction->transaction_status_id === 3;
        })->sum('amount');

        $totalExpense = $records->filter(function ($transaction) {
            return $transaction->transaction_type === 'expense' && $transaction->transaction_status_id === 3;
        })->sum('amount');

        $totalProfit = $totalIncome - $totalExpense;

        return [
            Stat::make('Total Profit', number_format($totalProfit, 2) . ' ZAR')
                ->description('Total Profit based on the selected criteria')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color($totalProfit >= 0 ? 'success' : 'danger'),

            Stat::make('Total Income', number_format($totalIncome, 2) . ' ZAR')
                ->description('Sum of all income transactions')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Total Expense', number_format($totalExpense, 2) . ' ZAR')
                ->description('Sum of all expense transactions')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('danger'),
        ];
    }
}

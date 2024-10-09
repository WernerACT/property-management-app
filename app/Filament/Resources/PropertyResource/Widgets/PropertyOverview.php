<?php

namespace App\Filament\Resources\PropertyResource\Widgets;

use App\Filament\Resources\PropertyResource\Pages\ListProperties;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PropertyOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListProperties::class;
    }

    protected function getStats(): array
    {
        // Fetch filtered properties from the table
        $properties = $this->getPageTableRecords();

        $totalIncome = 0;
        $totalExpense = 0;

        $startDate = session('start_date');
        $endDate = session('end_date');

        // Check if date range is set
        $dateRangeDescription = '';
        if ($startDate && $endDate) {
            $dateRangeDescription = " between $startDate and $endDate";
        }

            //check if start and end date is set and use that to filter through only transactions that were

        // Loop through the properties to get their related transactions
        foreach ($properties as $property) {
            $transactionsQuery = $property->transactions();

            // Check if the start and end dates are set and filter transactions accordingly
            if ($startDate && $endDate) {
                $transactionsQuery->whereBetween('date', [$startDate, $endDate]);
            }

            // Clone the original query for income and expense separately
            $incomeTransactions = (clone $transactionsQuery)
                ->where('transaction_type', '=', 'income')
                ->where('transaction_status_id', '=', 3)
                ->sum('amount');

            $expenseTransactions = (clone $transactionsQuery)
                ->where('transaction_type', '=', 'expense')
                ->where('transaction_status_id', '=', 3)
                ->sum('amount');

            // Sum up income and expenses
            $totalIncome += $incomeTransactions;
            $totalExpense += $expenseTransactions;
        }

        $totalProfit = $totalIncome - $totalExpense;

        return [
            Stat::make('Total Profit', number_format($totalProfit, 2) . ' ZAR')
                ->description('Total Profit based on the selected properties' . $dateRangeDescription)
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color($totalProfit >= 0 ? 'success' : 'danger'),

            Stat::make('Total Income', number_format($totalIncome, 2) . ' ZAR')
                ->description('Sum of all income transactions for selected properties' . $dateRangeDescription)
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Total Expense', number_format($totalExpense, 2) . ' ZAR')
                ->description('Sum of all expense transactions for selected properties' . $dateRangeDescription)
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('danger'),
        ];
    }
}

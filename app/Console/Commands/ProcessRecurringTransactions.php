<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Carbon\Carbon;

class ProcessRecurringTransactions extends Command
{
    protected $signature = 'transactions:process-recurring';
    protected $description = 'Process and create recurring transactions';

    public function handle()
    {
        // Get all active recurring transactions where the next run date is today or earlier
        $recurringTransactions = RecurringTransaction::where('is_active', true)
            ->whereDate('next_run_date', '<=', Carbon::now())
            ->get();

        foreach ($recurringTransactions as $recurring) {
            $transaction = $recurring->transaction;

            // Create a new transaction with status set to pending (1)
            Transaction::create([
                'property_id' => $transaction->property_id,
                'transaction_type_id' => $transaction->transaction_type_id,
                'transaction_status_id' => 1,  // Set as pending
                'amount' => $transaction->amount,
                'transaction_type' => $transaction->transaction_type,
                'date' => Carbon::now(),
                'comment' => $transaction->comment,
            ]);

            // Update the next run date based on the interval
            switch ($recurring->recurring_interval) {
                case 'daily':
                    $recurring->next_run_date = Carbon::now()->addDay();
                    break;
                case 'weekly':
                    $recurring->next_run_date = Carbon::now()->addWeek();
                    break;
                case 'monthly':
                    $recurring->next_run_date = Carbon::now()->addMonth();
                    break;
            }

            $recurring->save();
        }

        $this->info('Recurring transactions processed successfully.');
    }
}

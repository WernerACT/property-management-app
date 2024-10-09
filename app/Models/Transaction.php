<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'property_id',
        'transaction_type_id',
        'transaction_status_id',
        'amount',
        'transaction_type',
        'type',
        'comment',
        'recurring_interval',
        'is_recurring',
    ];

    protected static function booted()
    {
        static::created(function ($transaction) {
            // If the transaction is recurring, create a recurring transaction entry
            if ($transaction->is_recurring) {
                // Set next run date based on interval
                $nextRunDate = null;
                switch ($transaction->recurring_interval) {
                    case 'daily':
                        $nextRunDate = Carbon::parse($transaction->date)->addDay();
                        break;
                    case 'weekly':
                        $nextRunDate = Carbon::parse($transaction->date)->addWeek();
                        break;
                    case 'monthly':
                        $nextRunDate = Carbon::parse($transaction->date)->addMonth();
                        break;
                }

                RecurringTransaction::create([
                    'transaction_id' => $transaction->id,
                    'recurring_interval' => $transaction->recurring_interval,
                    'next_run_date' => $nextRunDate,
                ]);
            }
        });
    }

    public function transactionType(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function transactionStatus(): BelongsTo
    {
        return $this->belongsTo(TransactionStatus::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(TransactionDocument::class);
    }
}

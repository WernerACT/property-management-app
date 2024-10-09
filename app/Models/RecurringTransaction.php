<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'recurring_interval',
        'is_active',
        'next_run_date',
    ];

    // Relationship to Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}

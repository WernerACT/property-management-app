<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $fillable = [
        'account_type_id',
        'bank_account_type_id',
        'bank',
        'account_number',
        'branch_code'
        ];

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    public function bankAccountType(): BelongsTo
    {
        return $this->belongsTo(BankAccountType::class);
    }
}

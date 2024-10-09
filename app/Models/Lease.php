<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lease extends Model
{
    protected $fillable = [
        'tenant_id',
        'deposit',
        'deposit_received',
        'rental_amount',
        'start_date',
        'end_date'
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}

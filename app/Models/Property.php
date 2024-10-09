<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_type_id',
        'property_status_id',
        'entity_id',
        'managing_agent_id',
        'name',
        'purchase_value',
        'current_value',
        'purchase_date',
        'street_address',
        'address_line_2',
        'suburb',
        'city',
        'province',
        'postal_code',
    ];

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function managingAgent(): BelongsTo
    {
        return $this->belongsTo(ManagingAgent::class);
    }

    public function property_type(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function propertyStatus(): BelongsTo
    {
        return $this->belongsTo(PropertyStatus::class);
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    public function description()
    {
        return $this->hasOne(PropertyDescription::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function documents()
    {
        return $this->hasMany(PropertyDocument::class);
    }

    public function calculateExpenses(array $transactionTypes): float
    {
        return $this->transactions()
            ->whereHas('transaction_type', function ($query) use ($transactionTypes) {
                $query->whereIn('name', $transactionTypes);
            })
            ->sum('amount') ?? 0;
    }

    public function calculateTotalExpenses(): float
    {
        return $this->calculateExpenses(['Levy', 'Water', 'Electricity', 'Property Rates', 'Rental Commission', 'Loan Repayment']);
    }

    public function calculateIncome(array $transactionTypes): float
    {
        return $this->transactions()
            ->whereHas('transaction_type', function ($query) use ($transactionTypes) {
                $query->whereIn('name', $transactionTypes);
            })
            ->sum('amount') ?? 0;
    }

    public function calculateTotalIncome(): float
    {
        return $this->calculateIncome(['Rental Income', 'Municipal Income']);
    }

    public function calculateProfit(): float
    {
        return $this->calculateTotalIncome() - $this->calculateTotalExpenses();
    }

}

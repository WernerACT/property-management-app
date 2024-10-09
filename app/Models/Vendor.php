<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vendor extends Model
{
    protected $fillable = [
        'vendor_type_id',
        'display_name',
        'name',
        'email',
        'mobile_number',
        'office_number',
        'address'
    ];

    public function vendorType(): BelongsTo
    {
        return $this->belongsTo(VendorType::class);
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }
}

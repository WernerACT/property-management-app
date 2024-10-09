<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'vendor_id',
        'maintenance_item_id',
        'action',
        'amount',
        'status',
        'date',
        'comment',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function maintenanceItem()
    {
        return $this->belongsTo(MaintenanceItem::class);
    }
}

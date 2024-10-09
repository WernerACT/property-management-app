<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceItem extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }
}

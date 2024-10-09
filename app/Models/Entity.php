<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'id_number',
        'nickname',
    ];

    public function properties()
    {
        return $this->hasMany(Property::class, 'entity_id');
    }

    public function scopeWithPropertiesCount($query)
    {
        return $query->withCount('properties');
    }

    public function scopeWithPortfolioValue($query)
    {
        return $query->withSum('properties', 'current_value');
    }
}

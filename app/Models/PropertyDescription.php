<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'bedroom',
        'bathroom',
        'garage',
        'floor_size',
        'property_size',
        'parking',
        'description',
        'pet_friendly',
        'garden',
        'external_features',
        'other_features',
        'points_of_interest'
    ];

    protected $casts = [
        'external_features' => 'array',
        'other_features' => 'array',
        'points_of_interest' => 'array',
        'pet_friendly' => 'boolean',
        'garden' => 'boolean',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}

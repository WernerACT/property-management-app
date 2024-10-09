<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ManagingAgent extends Model
{
    protected $fillable = [
        'managing_agent_type_id',
        'address_id',
        'display_name',
        'name',
        'surname',
        'mobile_number',
        'office_number',
        'email'
    ];

    public function managingAgentType(): BelongsTo
    {
        return $this->belongsTo(ManagingAgentType::class);
    }


    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'entity_id');
    }
}

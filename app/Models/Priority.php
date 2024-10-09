<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Priority extends Model
{
    protected $table = 'priorities';

    protected $fillable = ['name'];

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
}

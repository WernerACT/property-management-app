<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    protected $table = 'statuses';

    protected $fillable = ['name'];

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
}

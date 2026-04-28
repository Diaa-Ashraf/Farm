<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeedType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function feedPurchases(): HasMany
    {
        return $this->hasMany(FeedPurchase::class);
    }
}

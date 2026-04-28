<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'cycle_id',
        'feed_type_id',
        'supplier_name',
        'supplier_phone',
        'unit',
        'quantity',
        'unit_price',
        'total',
        'purchase_date',
        'paid_amount',
        'remaining',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    public function feedType(): BelongsTo
    {
        return $this->belongsTo(FeedType::class);
    }
}

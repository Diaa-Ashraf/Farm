<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SawdustPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'cycle_id',
        'supplier_name',
        'supplier_phone',
        'quantity_tons',
        'price_per_ton',
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
}

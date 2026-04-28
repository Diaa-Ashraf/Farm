<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'cycle_id',
        'buyer_id',
        'sale_date',
        'truck_number',
        'total_weight_kg',
        'bird_count',
        'price_per_kg',
        'total',
        'paid_amount',
        'remaining',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }
}

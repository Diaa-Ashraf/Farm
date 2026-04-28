<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CycleClosing extends Model
{
    use HasFactory;

    protected $fillable = [
        'cycle_id',
        'closing_date',
        'initial_count',
        'mortality_count',
        'sold_count',
        'cycle_days',
        'chick_cost',
        'feed_cost',
        'feed_tons',
        'medicine_cost',
        'gas_cost',
        'sawdust_cost',
        'sawdust_tons',
        'worker_cost',
        'misc_cost',
        'total_expenses',
        'total_revenue',
        'total_weight_kg',
        'net_profit',
        'result',
        'fcr',
        'avg_weight_kg',
        'mortality_rate',
        'profit_per_kg',
        'notes',
    ];

    protected $casts = [
        'closing_date' => 'date',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }
}

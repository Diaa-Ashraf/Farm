<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'cycle_id',
        'party_name',
        'party_phone',
        'party_type',
        'type',
        'original_amount',
        'paid_amount',
        'remaining',
        'due_date',
        'status',
        'source_type',
        'source_id',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(FinancialPayment::class);
    }
}

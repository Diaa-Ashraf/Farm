<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'financial_record_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(FinancialRecord::class, 'financial_record_id');
    }
}

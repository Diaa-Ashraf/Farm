<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MiscellaneousExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'cycle_id',
        'worker_id',
        'description',
        'paid_to',
        'phone',
        'amount',
        'expense_date',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }
}

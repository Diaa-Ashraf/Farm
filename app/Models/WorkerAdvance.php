<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkerAdvance extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_cycle_id',
        'amount',
        'advance_date',
        'reason',
        'notes',
    ];

    protected $casts = [
        'advance_date' => 'date',
    ];

    public function workerCycle(): BelongsTo
    {
        return $this->belongsTo(WorkerCycle::class);
    }
}

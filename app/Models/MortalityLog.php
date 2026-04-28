<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MortalityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'cycle_id',
        'log_date',
        'count',
        'reason',
    ];

    protected $casts = [
        'log_date' => 'date',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }
}

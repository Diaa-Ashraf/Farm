<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkerCycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'cycle_id',
        'worker_id',
        'work_start_date',
        'work_end_date',
        'daily_wage',
        'actual_days',
        'gross_wage',
        'total_advances',
        'net_wage',
        'payment_status',
        'paid_amount',
        'remaining',
        'notes',
    ];

    protected $casts = [
        'work_start_date' => 'date',
        'work_end_date' => 'date',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function advances(): HasMany
    {
        return $this->hasMany(WorkerAdvance::class);
    }

    public function recalculateWages(): void
    {
        $actualDays = 0;

        if ($this->work_start_date && $this->work_end_date) {
            $actualDays = max(0, $this->work_start_date->diffInDays($this->work_end_date) + 1);
        }

        $grossWage = $this->daily_wage * $actualDays;
        
        // حساب السلف من جدول السلف ومن المصروفات النثرية المرتبطة بالعامل
        $advancesSum = $this->advances()->sum('amount');
        $miscAdvancesSum = \App\Models\MiscellaneousExpense::where('worker_id', $this->worker_id)
            ->where('cycle_id', $this->cycle_id)
            ->sum('amount');
            
        $totalAdvances = $advancesSum + $miscAdvancesSum;
        
        $netWage = max($grossWage - $totalAdvances, 0);
        $paidAmount = $this->paid_amount ?: 0;
        $remaining = max($netWage - $paidAmount, 0);
        
        $paymentStatus = $remaining <= 0
            ? 'paid'
            : ($paidAmount > 0 ? 'partial' : 'unpaid');

        $this->fill([
            'actual_days' => $actualDays,
            'gross_wage' => $grossWage,
            'total_advances' => $totalAdvances,
            'net_wage' => $netWage,
            'remaining' => $remaining,
            'payment_status' => $paymentStatus,
        ])->saveQuietly();
    }
}

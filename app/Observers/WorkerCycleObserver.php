<?php

namespace App\Observers;

use App\Models\WorkerCycle;

class WorkerCycleObserver
{
    public function saving(WorkerCycle $workerCycle): void
    {
        $actualDays = 0;

        if ($workerCycle->work_start_date && $workerCycle->work_end_date) {
            $actualDays = max(0, $workerCycle->work_start_date->diffInDays($workerCycle->work_end_date) + 1);
        }

        $workerCycle->actual_days = $actualDays;
        $workerCycle->gross_wage = $workerCycle->daily_wage * $actualDays;
        $workerCycle->total_advances = $workerCycle->advances()->sum('amount');
        $workerCycle->net_wage = $workerCycle->gross_wage - $workerCycle->total_advances;

        $paidAmount = $workerCycle->paid_amount ?: 0;
        $remaining = max($workerCycle->gross_wage - $paidAmount, 0);

        $workerCycle->remaining = $remaining;
        $workerCycle->payment_status = $remaining === 0 ? 'paid' : ($paidAmount > 0 ? 'partial' : 'unpaid');
    }
}

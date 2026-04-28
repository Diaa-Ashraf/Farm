<?php

namespace App\Observers;

use App\Models\WorkerAdvance;

class WorkerAdvanceObserver
{
    public function saved(WorkerAdvance $advance): void
    {
        $advance->workerCycle?->recalculateWages();
    }

    public function deleted(WorkerAdvance $advance): void
    {
        $advance->workerCycle?->recalculateWages();
    }
}

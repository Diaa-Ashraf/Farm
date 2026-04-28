<?php

namespace App\Observers;

use App\Models\Cycle;

class CycleObserver
{
    public function saving(Cycle $model): void
    {
        // حساب إجمالي تكلفة الكتاكيت تلقائي
        $model->chick_total = $model->initial_count * $model->chick_price;
    }
}

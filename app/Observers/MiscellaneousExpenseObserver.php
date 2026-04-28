<?php

namespace App\Observers;

use App\Models\MiscellaneousExpense;
use App\Models\FinancialRecord;

class MiscellaneousExpenseObserver
{
    public function saved(MiscellaneousExpense $model): void
    {
        // إنشاء أو تحديث سجل مالي
        FinancialRecord::updateOrCreate(
            ['source_type' => 'miscellaneous_expense', 'source_id' => $model->id],
            [
                'cycle_id'        => $model->cycle_id,
                'party_name'      => $model->paid_to ?? $model->description,
                'party_phone'     => $model->phone,
                'party_type'      => 'مصروف نثري',
                'type'            => 'payable',
                'original_amount' => $model->amount,
                'paid_amount'     => $model->payment_status === 'paid' ? $model->amount : 0,
                'remaining'       => $model->payment_status === 'paid' ? 0 : $model->amount,
                'status'          => $model->payment_status === 'paid' ? 'settled' : 'active',
            ]
        );
    }

    public function deleted(MiscellaneousExpense $model): void
    {
        // حذف السجل المالي عند حذف المصروف
        FinancialRecord::where('source_type', 'miscellaneous_expense')
            ->where('source_id', $model->id)
            ->delete();
    }
}

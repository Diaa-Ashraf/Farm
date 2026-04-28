<?php

namespace App\Observers;

use App\Models\Sale;
use App\Models\FinancialRecord;

class SaleObserver
{
    public function saving(Sale $model): void
    {
        // حساب الإجمالي تلقائي
        $model->total = $model->total_weight_kg * $model->price_per_kg;

        // حساب المتبقي تلقائي
        $paidAmount = $model->paid_amount ?? 0;
        $model->remaining = max(0, $model->total - $paidAmount);

        // تحديد حالة الدفع تلقائية
        $model->payment_status = match (true) {
            $model->remaining <= 0    => 'paid',
            $paidAmount > 0           => 'partial',
            default                   => 'unpaid',
        };
    }

    public function saved(Sale $model): void
    {
        // إنشاء أو تحديث سجل مالي (receivable)
        FinancialRecord::updateOrCreate(
            ['source_type' => 'sale', 'source_id' => $model->id],
            [
                'cycle_id'        => $model->cycle_id,
                'party_name'      => $model->buyer?->name ?? 'مشتري غير محدد',
                'party_phone'     => $model->buyer?->phone,
                'party_type'      => 'مشتري',
                'type'            => 'receivable',  // لينا — يدفعوا
                'original_amount' => $model->total,
                'paid_amount'     => $model->paid_amount ?? 0,
                'remaining'       => $model->remaining,
                'status'          => $model->remaining <= 0 ? 'settled' : 'active',
            ]
        );
    }

    public function deleted(Sale $model): void
    {
        // حذف السجل المالي عند حذف البيع
        FinancialRecord::where('source_type', 'sale')
            ->where('source_id', $model->id)
            ->delete();
    }
}

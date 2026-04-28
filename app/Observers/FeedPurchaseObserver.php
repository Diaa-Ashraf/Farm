<?php

namespace App\Observers;

use App\Models\FeedPurchase;
use App\Models\FinancialRecord;

class FeedPurchaseObserver
{
    public function saving(FeedPurchase $model): void
    {
        // حساب الإجمالي تلقائي
        $model->total = $model->quantity * $model->unit_price;

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

    public function saved(FeedPurchase $model): void
    {
        // إنشاء أو تحديث سجل مالي
        FinancialRecord::updateOrCreate(
            ['source_type' => 'feed_purchase', 'source_id' => $model->id],
            [
                'cycle_id'        => $model->cycle_id,
                'party_name'      => $model->supplier_name ?? 'مورد غير محدد',
                'party_phone'     => $model->supplier_phone,
                'party_type'      => 'مورد علف',
                'type'            => 'payable',
                'original_amount' => $model->total,
                'paid_amount'     => $model->paid_amount ?? 0,
                'remaining'       => $model->remaining,
                'status'          => $model->remaining <= 0 ? 'settled' : 'active',
            ]
        );
    }

    public function deleted(FeedPurchase $model): void
    {
        // حذف السجل المالي عند حذف الشراء
        FinancialRecord::where('source_type', 'feed_purchase')
            ->where('source_id', $model->id)
            ->delete();
    }
}

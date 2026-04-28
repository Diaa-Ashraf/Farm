<?php

namespace App\Observers;

use App\Models\MedicineInvoice;
use App\Models\FinancialRecord;

class MedicineInvoiceObserver
{
    public function saving(MedicineInvoice $model): void
    {
        // حساب الإجمالي من البنود
        $model->total = $model->items()->sum('total');

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

    public function saved(MedicineInvoice $model): void
    {
        // إنشاء أو تحديث سجل مالي
        FinancialRecord::updateOrCreate(
            ['source_type' => 'medicine_invoice', 'source_id' => $model->id],
            [
                'cycle_id'        => $model->cycle_id,
                'party_name'      => $model->pharmacy_name ?? 'صيدلية غير محددة',
                'party_phone'     => $model->pharmacy_phone,
                'party_type'      => 'صيدلية',
                'type'            => 'payable',
                'original_amount' => $model->total,
                'paid_amount'     => $model->paid_amount ?? 0,
                'remaining'       => $model->remaining,
                'status'          => $model->remaining <= 0 ? 'settled' : 'active',
            ]
        );
    }

    public function deleted(MedicineInvoice $model): void
    {
        // حذف السجل المالي عند حذف الفاتورة
        FinancialRecord::where('source_type', 'medicine_invoice')
            ->where('source_id', $model->id)
            ->delete();
    }
}

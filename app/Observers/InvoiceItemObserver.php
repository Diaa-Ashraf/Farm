<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class InvoiceItemObserver
{
    public function saving(Model $model): void
    {
        if (isset($model->quantity, $model->unit_price)) {
            $model->total = $model->quantity * $model->unit_price;
        }
    }

    public function saved(Model $model): void
    {
        $this->recalculateInvoice($model);
    }

    public function deleted(Model $model): void
    {
        $this->recalculateInvoice($model);
    }

    protected function recalculateInvoice(Model $model): void
    {
        if (! method_exists($model, 'invoice')) {
            return;
        }

        $invoice = $model->invoice;

        if ($invoice && method_exists($invoice, 'recalculateTotals')) {
            $invoice->recalculateTotals();
        }
    }
}

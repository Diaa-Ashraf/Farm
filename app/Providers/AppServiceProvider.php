<?php

namespace App\Providers;

use App\Models\Cycle;
use App\Models\FeedPurchase;
use App\Models\GasInvoice;
use App\Models\GasInvoiceItem;
use App\Models\MedicineInvoice;
use App\Models\MedicineInvoiceItem;
use App\Models\MiscellaneousExpense;
use App\Models\Sale;
use App\Models\SawdustPurchase;
use App\Models\WorkerAdvance;
use App\Models\WorkerCycle;
use App\Observers\CycleObserver;
use App\Observers\FeedPurchaseObserver;
use App\Observers\GasInvoiceObserver;
use App\Observers\InvoiceItemObserver;
use App\Observers\MedicineInvoiceObserver;
use App\Observers\MiscellaneousExpenseObserver;
use App\Observers\SaleObserver;
use App\Observers\SawdustPurchaseObserver;
use App\Observers\WorkerAdvanceObserver;
use App\Observers\WorkerCycleObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cycle::observe(CycleObserver::class);
        FeedPurchase::observe(FeedPurchaseObserver::class);
        SawdustPurchase::observe(SawdustPurchaseObserver::class);
        MedicineInvoice::observe(MedicineInvoiceObserver::class);
        MedicineInvoiceItem::observe(InvoiceItemObserver::class);
        GasInvoice::observe(GasInvoiceObserver::class);
        GasInvoiceItem::observe(InvoiceItemObserver::class);
        MiscellaneousExpense::observe(MiscellaneousExpenseObserver::class);
        Sale::observe(SaleObserver::class);
        WorkerAdvance::observe(WorkerAdvanceObserver::class);
        WorkerCycle::observe(WorkerCycleObserver::class);
    }
}

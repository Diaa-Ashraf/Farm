# Farm Management System — Filament 5 Context
# Laravel 12 + Filament v5 فقط — بدون API / Controller / Service / Repository
# ادي الملف ده للـ AI قبل ما تطلب منه أي كود

---

## المشروع

نظام إدارة مزرعة دواجن كامل.
**Stack:** Laravel 12 + Filament v5 فقط.
**مفيش:** API / Controllers / Services / Repositories.
**كل الشغل** بيتعمل من خلال Filament Admin Panel.

---

## طريقة الشغل

```
المستخدم بيفتح Filament
        ↓
يدخل البيانات من Resources
        ↓
Filament بيتعامل مع Eloquent مباشرة
        ↓
السيستم يحسب الأرقام تلقائي من Model Observers أو Filament Actions
```

---

## قواعد ثابتة — لا تحيد عنها

```
✅ Eloquent دايماً — مش Query Builder
✅ Eager Loading في getEloquentQuery()
✅ Cache في الـ Model أو Widget
✅ components() مش schema() جوا Section
✅ Arabic labels على كل field
✅ navigationGroup عربي
✅ Model Observers للحسابات التلقائية
✅ Filament Actions لتقفيل الدورة
❌ مش Controllers
❌ مش Services
❌ مش Repositories
❌ مش Query Builder
❌ مش JOIN
```

---

## Database — الجداول والعلاقات

```
farms
  └── cycles (الدورات)
        ├── feed_purchases         مشتريات الأعلاف
        ├── medicine_invoices      فواتير الأدوية
        │     └── medicine_invoice_items
        ├── gas_invoices           فواتير الغاز
        │     └── gas_invoice_items
        ├── sawdust_purchases      النشارة
        ├── worker_cycles          العمال في الدورة
        │     └── worker_advances  سلف العمال
        ├── miscellaneous_expenses مصاريف نثرية
        ├── mortality_logs         نافق يومي
        ├── sales                  مبيعات
        ├── cycle_closings         تقفيل (auto)
        └── financial_records      ديون (auto)
              └── financial_payments

farms → workers
buyers (مستقل)
feed_types / gas_types (lookup tables)
```

---

## Models والـ Relations الكاملة

```php
// ════════════════════════════════
// Farm
// ════════════════════════════════
class Farm extends Model
{
    protected $fillable = ['name', 'location', 'capacity', 'notes'];

    public function cycles()
    {
        return $this->hasMany(Cycle::class);
    }

    public function workers()
    {
        return $this->hasMany(Worker::class);
    }

    public function activeCycle()
    {
        return $this->hasOne(Cycle::class)->where('status', 'active');
    }
}


// ════════════════════════════════
// Cycle
// ════════════════════════════════
class Cycle extends Model
{
    protected $fillable = [
        'farm_id', 'name', 'start_date', 'end_date',
        'initial_count', 'chick_price', 'chick_total', 'status', 'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function farm()                 { return $this->belongsTo(Farm::class); }
    public function feedPurchases()        { return $this->hasMany(FeedPurchase::class); }
    public function medicineInvoices()     { return $this->hasMany(MedicineInvoice::class); }
    public function gasInvoices()          { return $this->hasMany(GasInvoice::class); }
    public function sawdustPurchases()     { return $this->hasMany(SawdustPurchase::class); }
    public function workerCycles()         { return $this->hasMany(WorkerCycle::class); }
    public function miscExpenses()         { return $this->hasMany(MiscellaneousExpense::class); }
    public function mortalityLogs()        { return $this->hasMany(MortalityLog::class); }
    public function sales()                { return $this->hasMany(Sale::class); }
    public function closing()              { return $this->hasOne(CycleClosing::class); }
    public function financialRecords()     { return $this->hasMany(FinancialRecord::class); }

    // اليوم الكام من الدورة
    public function getDayNumberAttribute(): int
    {
        return $this->start_date->diffInDays(now()) + 1;
    }

    // الطيور المتبقية
    public function getRemainingBirdsAttribute(): int
    {
        $dead = $this->mortalityLogs()->sum('count');
        $sold = $this->sales()->sum('bird_count') ?? 0;
        return $this->initial_count - $dead - $sold;
    }
}


// ════════════════════════════════
// FeedPurchase
// ════════════════════════════════
class FeedPurchase extends Model
{
    protected $fillable = [
        'cycle_id', 'feed_type_id', 'supplier_name', 'supplier_phone',
        'unit', 'quantity', 'unit_price', 'total',
        'purchase_date', 'paid_amount', 'remaining', 'payment_status', 'notes'
    ];

    protected $casts = ['purchase_date' => 'date'];

    public function cycle()    { return $this->belongsTo(Cycle::class); }
    public function feedType() { return $this->belongsTo(FeedType::class); }
}


// ════════════════════════════════
// MedicineInvoice
// ════════════════════════════════
class MedicineInvoice extends Model
{
    protected $fillable = [
        'cycle_id', 'pharmacy_name', 'pharmacy_phone',
        'invoice_date', 'total', 'paid_amount', 'remaining', 'payment_status', 'notes'
    ];

    protected $casts = ['invoice_date' => 'date'];

    public function cycle() { return $this->belongsTo(Cycle::class); }
    public function items() { return $this->hasMany(MedicineInvoiceItem::class); }
}


// ════════════════════════════════
// MedicineInvoiceItem
// ════════════════════════════════
class MedicineInvoiceItem extends Model
{
    protected $fillable = [
        'medicine_invoice_id', 'medicine_name', 'unit',
        'quantity', 'unit_price', 'total'
    ];

    public function invoice() { return $this->belongsTo(MedicineInvoice::class); }
}


// ════════════════════════════════
// GasInvoice
// ════════════════════════════════
class GasInvoice extends Model
{
    protected $fillable = [
        'cycle_id', 'supplier_name', 'supplier_phone',
        'invoice_date', 'total', 'paid_amount', 'remaining', 'payment_status', 'notes'
    ];

    protected $casts = ['invoice_date' => 'date'];

    public function cycle() { return $this->belongsTo(Cycle::class); }
    public function items() { return $this->hasMany(GasInvoiceItem::class); }
}


// ════════════════════════════════
// GasInvoiceItem
// ════════════════════════════════
class GasInvoiceItem extends Model
{
    protected $fillable = [
        'gas_invoice_id', 'gas_type_id',
        'quantity', 'unit_price', 'total'
    ];

    public function invoice() { return $this->belongsTo(GasInvoice::class); }
    public function gasType() { return $this->belongsTo(GasType::class); }
}


// ════════════════════════════════
// SawdustPurchase
// ════════════════════════════════
class SawdustPurchase extends Model
{
    protected $fillable = [
        'cycle_id', 'supplier_name', 'supplier_phone',
        'quantity_tons', 'price_per_ton', 'total',
        'purchase_date', 'paid_amount', 'remaining', 'payment_status', 'notes'
    ];

    protected $casts = ['purchase_date' => 'date'];

    public function cycle() { return $this->belongsTo(Cycle::class); }
}


// ════════════════════════════════
// Worker
// ════════════════════════════════
class Worker extends Model
{
    protected $fillable = [
        'farm_id', 'name', 'phone', 'daily_wage', 'status', 'notes'
    ];

    public function farm()         { return $this->belongsTo(Farm::class); }
    public function workerCycles() { return $this->hasMany(WorkerCycle::class); }
}


// ════════════════════════════════
// WorkerCycle
// ════════════════════════════════
class WorkerCycle extends Model
{
    protected $fillable = [
        'cycle_id', 'worker_id', 'work_start_date', 'work_end_date',
        'daily_wage', 'actual_days', 'gross_wage', 'total_advances',
        'net_wage', 'payment_status', 'paid_amount', 'remaining', 'notes'
    ];

    protected $casts = [
        'work_start_date' => 'date',
        'work_end_date'   => 'date',
    ];

    public function cycle()    { return $this->belongsTo(Cycle::class); }
    public function worker()   { return $this->belongsTo(Worker::class); }
    public function advances() { return $this->hasMany(WorkerAdvance::class); }

    // الأيام الفعلية
    public function getActualDaysAttribute(): int
    {
        $end = $this->work_end_date ?? now();
        return $this->work_start_date->diffInDays($end);
    }

    // إجمالي الراتب قبل السلف
    public function getGrossWageAttribute(): float
    {
        return $this->actual_days * $this->daily_wage;
    }

    // إجمالي السلف
    public function getTotalAdvancesAmountAttribute(): float
    {
        return $this->advances()->sum('amount');
    }

    // الصافي = الراتب - السلف
    public function getNetWageAmountAttribute(): float
    {
        return $this->gross_wage - $this->total_advances_amount;
    }
}


// ════════════════════════════════
// WorkerAdvance
// ════════════════════════════════
class WorkerAdvance extends Model
{
    protected $fillable = [
        'worker_cycle_id', 'amount', 'advance_date', 'reason', 'notes'
    ];

    protected $casts = ['advance_date' => 'date'];

    public function workerCycle() { return $this->belongsTo(WorkerCycle::class); }
}


// ════════════════════════════════
// MiscellaneousExpense
// ════════════════════════════════
class MiscellaneousExpense extends Model
{
    protected $fillable = [
        'cycle_id', 'description', 'paid_to', 'phone',
        'amount', 'expense_date', 'payment_status', 'notes'
    ];

    protected $casts = ['expense_date' => 'date'];

    public function cycle() { return $this->belongsTo(Cycle::class); }
}


// ════════════════════════════════
// MortalityLog
// ════════════════════════════════
class MortalityLog extends Model
{
    protected $fillable = [
        'cycle_id', 'log_date', 'count', 'reason'
    ];

    protected $casts = ['log_date' => 'date'];

    public function cycle() { return $this->belongsTo(Cycle::class); }
}


// ════════════════════════════════
// Buyer
// ════════════════════════════════
class Buyer extends Model
{
    protected $fillable = ['name', 'phone', 'truck_number', 'notes'];

    public function sales() { return $this->hasMany(Sale::class); }
}


// ════════════════════════════════
// Sale
// ════════════════════════════════
class Sale extends Model
{
    protected $fillable = [
        'cycle_id', 'buyer_id', 'sale_date', 'truck_number',
        'total_weight_kg', 'bird_count', 'price_per_kg', 'total',
        'paid_amount', 'remaining', 'payment_status', 'notes'
    ];

    protected $casts = ['sale_date' => 'date'];

    public function cycle() { return $this->belongsTo(Cycle::class); }
    public function buyer() { return $this->belongsTo(Buyer::class); }
}


// ════════════════════════════════
// CycleClosing
// ════════════════════════════════
class CycleClosing extends Model
{
    protected $fillable = [
        'cycle_id', 'closing_date',
        'initial_count', 'mortality_count', 'sold_count', 'cycle_days',
        'chick_cost', 'feed_cost', 'feed_tons', 'medicine_cost',
        'gas_cost', 'sawdust_cost', 'sawdust_tons', 'worker_cost',
        'misc_cost', 'total_expenses', 'total_revenue', 'total_weight_kg',
        'net_profit', 'result', 'fcr', 'avg_weight_kg', 'mortality_rate',
        'profit_per_kg', 'notes'
    ];

    protected $casts = ['closing_date' => 'date'];

    public function cycle() { return $this->belongsTo(Cycle::class); }
}


// ════════════════════════════════
// FinancialRecord
// ════════════════════════════════
class FinancialRecord extends Model
{
    protected $fillable = [
        'cycle_id', 'party_name', 'party_phone', 'party_type',
        'type', 'original_amount', 'paid_amount', 'remaining',
        'due_date', 'status', 'source_type', 'source_id', 'notes'
    ];

    protected $casts = ['due_date' => 'date'];

    public function cycle()    { return $this->belongsTo(Cycle::class); }
    public function payments() { return $this->hasMany(FinancialPayment::class); }
}


// ════════════════════════════════
// FinancialPayment
// ════════════════════════════════
class FinancialPayment extends Model
{
    protected $fillable = [
        'financial_record_id', 'amount', 'payment_date', 'payment_method', 'notes'
    ];

    protected $casts = ['payment_date' => 'date'];

    public function record() { return $this->belongsTo(FinancialRecord::class); }
}
```

---

## Model Observers — الحسابات التلقائية

```php
// ════════════════════════════════
// كل Observer بيشتغل تلقائي لما يتعمل save/create/update
// سجلهم في AppServiceProvider::boot()
// ════════════════════════════════

// FeedPurchaseObserver
// لما بتسجل شراء علف → يحسب total و remaining تلقائي
class FeedPurchaseObserver
{
    public function saving(FeedPurchase $model): void
    {
        // total يتحسب تلقائي
        $model->total = $model->quantity * $model->unit_price;

        // remaining يتحسب تلقائي
        $model->remaining = $model->total - $model->paid_amount;

        // payment_status يتحدد تلقائي
        $model->payment_status = match(true) {
            $model->remaining <= 0       => 'paid',
            $model->paid_amount > 0      => 'partial',
            default                      => 'unpaid',
        };
    }

    public function saved(FeedPurchase $model): void
    {
        // financial_record يتعمل أو يتحدث تلقائي
        FinancialRecord::updateOrCreate(
            ['source_type' => 'feed_purchase', 'source_id' => $model->id],
            [
                'cycle_id'        => $model->cycle_id,
                'party_name'      => $model->supplier_name ?? 'مورد غير محدد',
                'party_phone'     => $model->supplier_phone,
                'party_type'      => 'مورد علف',
                'type'            => 'payable',
                'original_amount' => $model->total,
                'paid_amount'     => $model->paid_amount,
                'remaining'       => $model->remaining,
                'status'          => $model->remaining <= 0 ? 'settled' : 'active',
            ]
        );
    }
}

// نفس الـ Observer لـ:
// SawdustPurchaseObserver → party_type = 'مورد نشارة'
// MedicineInvoiceObserver → party_type = 'صيدلية'
// GasInvoiceObserver      → party_type = 'مورد غاز'

// ────────────────────────────────
// MedicineInvoiceItemObserver
// لما بتضيف صنف دواء → يحسب total البند و يحدث إجمالي الفاتورة
class MedicineInvoiceItemObserver
{
    public function saving(MedicineInvoiceItem $item): void
    {
        $item->total = $item->quantity * $item->unit_price;
    }

    public function saved(MedicineInvoiceItem $item): void
    {
        // حدث إجمالي الفاتورة تلقائي
        $invoice = $item->invoice;
        $invoice->total     = $invoice->items()->sum('total');
        $invoice->remaining = $invoice->total - $invoice->paid_amount;
        $invoice->payment_status = match(true) {
            $invoice->remaining <= 0  => 'paid',
            $invoice->paid_amount > 0 => 'partial',
            default                   => 'unpaid',
        };
        $invoice->save();
    }
}

// نفس الكلام لـ GasInvoiceItemObserver

// ────────────────────────────────
// WorkerAdvanceObserver
// لما بتسجل سلفة → يحدث worker_cycle تلقائي
class WorkerAdvanceObserver
{
    public function saved(WorkerAdvance $advance): void
    {
        $wc = $advance->workerCycle;
        $wc->total_advances = $wc->advances()->sum('amount');
        $wc->net_wage       = $wc->gross_wage - $wc->total_advances;
        $wc->remaining      = $wc->net_wage - $wc->paid_amount;
        $wc->save();
    }
}

// ────────────────────────────────
// SaleObserver
// لما بتسجل بيعة → يحسب total ويعمل financial_record
class SaleObserver
{
    public function saving(Sale $sale): void
    {
        $sale->total     = $sale->total_weight_kg * $sale->price_per_kg;
        $sale->remaining = $sale->total - $sale->paid_amount;
        $sale->payment_status = match(true) {
            $sale->remaining <= 0  => 'paid',
            $sale->paid_amount > 0 => 'partial',
            default                => 'unpaid',
        };
    }

    public function saved(Sale $sale): void
    {
        if ($sale->remaining > 0) {
            FinancialRecord::updateOrCreate(
                ['source_type' => 'sale', 'source_id' => $sale->id],
                [
                    'cycle_id'        => $sale->cycle_id,
                    'party_name'      => $sale->buyer?->name ?? 'مشتري غير محدد',
                    'party_phone'     => $sale->buyer?->phone,
                    'party_type'      => 'مشتري',
                    'type'            => 'receivable',
                    'original_amount' => $sale->total,
                    'paid_amount'     => $sale->paid_amount,
                    'remaining'       => $sale->remaining,
                    'status'          => $sale->remaining <= 0 ? 'settled' : 'active',
                ]
            );
        }
    }
}

// ────────────────────────────────
// CycleObserver
// لما بتعمل Cycle جديدة → يحسب chick_total تلقائي
class CycleObserver
{
    public function saving(Cycle $cycle): void
    {
        $cycle->chick_total = $cycle->initial_count * $cycle->chick_price;
    }
}

// ════════════════════════════════
// تسجيل الـ Observers في AppServiceProvider
// ════════════════════════════════
// في app/Providers/AppServiceProvider.php → boot()

Cycle::observe(CycleObserver::class);
FeedPurchase::observe(FeedPurchaseObserver::class);
MedicineInvoice::observe(MedicineInvoiceObserver::class);
MedicineInvoiceItem::observe(MedicineInvoiceItemObserver::class);
GasInvoice::observe(GasInvoiceObserver::class);
GasInvoiceItem::observe(GasInvoiceItemObserver::class);
SawdustPurchase::observe(SawdustPurchaseObserver::class);
WorkerAdvance::observe(WorkerAdvanceObserver::class);
Sale::observe(SaleObserver::class);
```

---

## Filament Resources — كل Resource بالتفصيل

```
app/Filament/Resources/
├── Farms/
│     FarmResource.php
│     Schemas/FarmForm.php
│     Tables/FarmsTable.php
│     Pages/ListFarms.php
│     Pages/CreateFarm.php
│     Pages/EditFarm.php
│
├── Cycles/
│     CycleResource.php        ← فيه Action لتقفيل الدورة
│     Schemas/CycleForm.php
│     Tables/CyclesTable.php
│     Pages/ListCycles.php
│     Pages/CreateCycle.php
│     Pages/EditCycle.php
│     Pages/ViewCycle.php      ← صفحة تفاصيل مع widgets
│
├── FeedPurchases/
├── MedicineInvoices/          ← فيه RelationManager للبنود
├── GasInvoices/               ← فيه RelationManager للبنود
├── SawdustPurchases/
├── WorkerCycles/              ← فيه RelationManager للسلف
├── MiscellaneousExpenses/
├── MortalityLogs/
├── Buyers/
├── Sales/
├── CycleClosings/             ← View Only
├── FinancialRecords/          ← فيه Action لتسجيل دفعة
└── Settings/                  ← FeedTypes + GasTypes
```

---

## CycleResource — Action التقفيل

```php
// في CycleResource أو Pages/ViewCycle.php
// Action يظهر بس لو الدورة status = 'active'

Action::make('close_cycle')
    ->label('تقفيل الدورة')
    ->icon('heroicon-o-lock-closed')
    ->color('danger')
    ->requiresConfirmation()
    ->modalHeading('تأكيد تقفيل الدورة')
    ->modalDescription('سيتم حساب كل المصاريف والإيرادات تلقائياً وإغلاق الدورة.')
    ->visible(fn(Cycle $record) => $record->status === 'active')
    ->action(function (Cycle $cycle) {
        // جيب كل البيانات بـ Eager Loading
        $cycle->load([
            'feedPurchases',
            'medicineInvoices',
            'gasInvoices',
            'sawdustPurchases',
            'workerCycles.advances',
            'miscExpenses',
            'sales',
            'mortalityLogs',
        ]);

        // احسب كل حاجة
        $cycleEnd     = now();
        $cycleDays    = $cycle->start_date->diffInDays($cycleEnd);
        $feedTons     = $cycle->feedPurchases->where('unit', 'ton')->sum('quantity');
        $totalWeight  = $cycle->sales->sum('total_weight_kg');
        $soldCount    = $cycle->sales->sum('bird_count') ?? 0;
        $mortalityCount = $cycle->mortalityLogs->sum('count');

        $costs = [
            'chick_cost'    => $cycle->chick_total,
            'feed_cost'     => $cycle->feedPurchases->sum('total'),
            'medicine_cost' => $cycle->medicineInvoices->sum('total'),
            'gas_cost'      => $cycle->gasInvoices->sum('total'),
            'sawdust_cost'  => $cycle->sawdustPurchases->sum('total'),
            'worker_cost'   => $cycle->workerCycles->sum('net_wage'),
            'misc_cost'     => $cycle->miscExpenses->sum('amount'),
        ];

        $totalExpenses = array_sum($costs);
        $totalRevenue  = $cycle->sales->sum('total');
        $netProfit     = $totalRevenue - $totalExpenses;

        // حفظ التقفيل
        CycleClosing::create([
            'cycle_id'        => $cycle->id,
            'closing_date'    => $cycleEnd,
            'initial_count'   => $cycle->initial_count,
            'mortality_count' => $mortalityCount,
            'sold_count'      => $soldCount,
            'cycle_days'      => $cycleDays,
            ...$costs,
            'feed_tons'       => $feedTons,
            'sawdust_tons'    => $cycle->sawdustPurchases->sum('quantity_tons'),
            'total_expenses'  => $totalExpenses,
            'total_revenue'   => $totalRevenue,
            'total_weight_kg' => $totalWeight,
            'net_profit'      => $netProfit,
            'result'          => $netProfit > 0 ? 'profit' : ($netProfit < 0 ? 'loss' : 'break_even'),
            'fcr'             => $totalWeight > 0 ? round(($feedTons * 1000) / $totalWeight, 3) : null,
            'avg_weight_kg'   => $soldCount > 0 ? round($totalWeight / $soldCount, 3) : null,
            'mortality_rate'  => $cycle->initial_count > 0
                                    ? round(($mortalityCount / $cycle->initial_count) * 100, 2)
                                    : null,
            'profit_per_kg'   => $totalWeight > 0 ? round($netProfit / $totalWeight, 2) : null,
        ]);

        // حدث worker_cycles بالأيام الفعلية
        $cycle->workerCycles->each(function ($wc) use ($cycleEnd) {
            $wc->work_end_date  = $wc->work_end_date ?? $cycleEnd;
            $wc->actual_days    = $wc->actual_days;
            $wc->gross_wage     = $wc->gross_wage;
            $wc->total_advances = $wc->advances->sum('amount');
            $wc->net_wage       = $wc->gross_wage - $wc->total_advances;
            $wc->remaining      = $wc->net_wage - $wc->paid_amount;
            $wc->save();
        });

        // أقفل الدورة
        $cycle->update(['status' => 'closed', 'end_date' => $cycleEnd]);

        Notification::make()
            ->title('تم تقفيل الدورة بنجاح ✅')
            ->success()
            ->send();
    });
```

---

## MedicineInvoiceResource — RelationManager للبنود

```php
// RelationManagers/MedicineInvoiceItemsRelationManager.php

class MedicineInvoiceItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'أصناف الدواء';

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('medicine_name')->label('اسم الدواء')->required(),
            TextInput::make('unit')->label('الوحدة')->default('علبة'),
            TextInput::make('quantity')->label('الكمية')->numeric()->required(),
            TextInput::make('unit_price')->label('سعر الوحدة')->numeric()->required(),
            // total يتحسب تلقائي من Observer
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('medicine_name')->label('اسم الدواء'),
            TextColumn::make('quantity')->label('الكمية'),
            TextColumn::make('unit')->label('الوحدة'),
            TextColumn::make('unit_price')->label('السعر')->money('EGP'),
            TextColumn::make('total')->label('الإجمالي')->money('EGP'),
        ])->headerActions([
            CreateAction::make()->label('إضافة صنف'),
        ])->recordActions([
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }
}
```

---

## FinancialRecordResource — Action تسجيل دفعة

```php
Action::make('add_payment')
    ->label('تسجيل دفعة')
    ->icon('heroicon-o-banknotes')
    ->color('success')
    ->visible(fn(FinancialRecord $r) => $r->status === 'active')
    ->form([
        TextInput::make('amount')
            ->label('المبلغ')
            ->numeric()
            ->required(),
        DatePicker::make('payment_date')
            ->label('التاريخ')
            ->default(now()),
        TextInput::make('payment_method')
            ->label('طريقة الدفع')
            ->placeholder('كاش / تحويل'),
    ])
    ->action(function (FinancialRecord $record, array $data) {
        // سجل الدفعة
        FinancialPayment::create([
            'financial_record_id' => $record->id,
            'amount'              => $data['amount'],
            'payment_date'        => $data['payment_date'],
            'payment_method'      => $data['payment_method'] ?? null,
        ]);

        // حدث الرصيد
        $record->paid_amount += $data['amount'];
        $record->remaining    = $record->original_amount - $record->paid_amount;
        $record->status       = $record->remaining <= 0 ? 'settled' : 'active';
        $record->save();

        // حدث الجدول الأصلي (feed_purchase مثلاً)
        if ($record->source_type && $record->source_id) {
            $sourceModel = match($record->source_type) {
                'feed_purchase'    => FeedPurchase::find($record->source_id),
                'medicine_invoice' => MedicineInvoice::find($record->source_id),
                'gas_invoice'      => GasInvoice::find($record->source_id),
                'sawdust_purchase' => SawdustPurchase::find($record->source_id),
                'sale'             => Sale::find($record->source_id),
                default            => null,
            };

            $sourceModel?->update([
                'paid_amount'    => $record->paid_amount,
                'remaining'      => $record->remaining,
                'payment_status' => $record->remaining <= 0 ? 'paid'
                    : ($record->paid_amount > 0 ? 'partial' : 'unpaid'),
            ]);
        }
    });
```

---

## Widgets للـ Dashboard

```php
// ViewCycle Page → فيه Stats Overview

StatsOverviewWidget → يعرض:
  - اليوم الكام من الدورة: $cycle->day_number
  - الطيور المتبقية:       $cycle->remaining_birds
  - إجمالي المصاريف حتى الآن
  - إجمالي الإيرادات حتى الآن
  - الفلوس اللي علينا (payable)
  - الفلوس اللي لينا (receivable)
```

---

## Navigation Groups

```php
protected static string|UnitEnum|null $navigationGroup = 'إدارة المزرعة';
// المجموعات:
// 'إدارة المزرعة'    → Farm, Cycle
// 'المصروفات'        → FeedPurchase, MedicineInvoice, GasInvoice, SawdustPurchase, WorkerCycle, MiscExpense
// 'المتابعة اليومية' → MortalityLog, Sale
// 'الحسابات'         → CycleClosing, FinancialRecord, Buyer
// 'الإعدادات'        → FeedType, GasType
```

---

## Eager Loading في كل Resource

```php
// دايماً في getEloquentQuery()
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->with(['farm:id,name']);
        // أضيف الـ relations المطلوبة حسب الـ Resource
}

// Cycle Resource:
->with(['farm:id,name', 'closing'])

// WorkerCycle Resource:
->with(['worker:id,name', 'cycle:id,name,farm_id', 'advances'])

// MedicineInvoice Resource:
->with(['cycle:id,name', 'items'])

// FinancialRecord Resource:
->with(['cycle:id,name', 'payments'])
```

---

## Cache في الـ Widgets

```php
// في StatsOverviewWidget أو أي Widget
protected function getStats(): array
{
    $cycleId = $this->cycle->id;

    $stats = Cache::remember("cycle_{$cycleId}_stats", now()->addMinutes(5), function () {
        $cycle = Cycle::with([
            'feedPurchases',
            'medicineInvoices',
            'gasInvoices',
            'sawdustPurchases',
            'workerCycles.advances',
            'miscExpenses',
            'sales',
            'mortalityLogs',
        ])->find($this->cycle->id);

        return [
            'total_expenses' => $cycle->feedPurchases->sum('total')
                              + $cycle->medicineInvoices->sum('total')
                              + $cycle->gasInvoices->sum('total')
                              + $cycle->sawdustPurchases->sum('total')
                              + $cycle->workerCycles->sum('net_wage')
                              + $cycle->miscExpenses->sum('amount'),
            'total_revenue'  => $cycle->sales->sum('total'),
            'mortality_count'=> $cycle->mortalityLogs->sum('count'),
        ];
    });

    return [
        Stat::make('اليوم', "اليوم {$this->cycle->day_number}"),
        Stat::make('الطيور المتبقية', number_format($this->cycle->remaining_birds)),
        Stat::make('إجمالي المصاريف', number_format($stats['total_expenses']) . ' ج.م'),
        Stat::make('إجمالي الإيرادات', number_format($stats['total_revenue']) . ' ج.م'),
    ];
}

// امسح الـ cache بعد أي تعديل في Observer:
Cache::forget("cycle_{$model->cycle_id}_stats");
```

<?php

namespace App\Filament\Pages;

use App\Models\Cycle;
use App\Models\FeedPurchase;
use App\Models\FinancialRecord;
use App\Models\GasInvoice;
use App\Models\MedicineInvoice;
use App\Models\MiscellaneousExpense;
use App\Models\Sale;
use App\Models\SawdustPurchase;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;

class Reports extends Page
{
    protected static ?string $slug = 'reports';

    protected static ?string $title = 'التقارير التحليلية';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'التقارير';

    protected static ?int $navigationSort = 1;

    public $cycle_id;
    public $date_from;
    public $date_to;

    public static function getNavigationLabel(): string
    {
        return 'التقارير المالية';
    }

    protected function applyFilters(Builder $query, string $dateColumn = 'created_at'): Builder
    {
        return $query
            ->when($this->cycle_id, fn($q) => $q->where('cycle_id', $this->cycle_id))
            ->when($this->date_from, fn($q) => $q->whereDate($dateColumn, '>=', $this->date_from))
            ->when($this->date_to, fn($q) => $q->whereDate($dateColumn, '<=', $this->date_to));
    }

    public function content(Schema $schema): Schema
    {
        // حساب الإيرادات
        $salesQuery = $this->applyFilters(Sale::query(), 'sale_date');
        $totalRevenues = $salesQuery->sum('total');
        $collectedRevenues = $salesQuery->sum('paid_amount');
        $remainingRevenues = $salesQuery->sum('remaining');

        // حساب المصروفات
        $feedExp = $this->applyFilters(FeedPurchase::query(), 'purchase_date')->sum('total');
        $medExp = $this->applyFilters(MedicineInvoice::query(), 'invoice_date')->sum('total');
        $gasExp = $this->applyFilters(GasInvoice::query(), 'invoice_date')->sum('total');
        $sawdustExp = $this->applyFilters(SawdustPurchase::query(), 'purchase_date')->sum('total');
        $miscExp = $this->applyFilters(MiscellaneousExpense::query(), 'expense_date')->sum('amount');
        
        // إجمالي المصروفات
        $totalExpenses = $feedExp + $medExp + $gasExp + $sawdustExp + $miscExp;
        
        // صافي الربح
        $netProfit = $totalRevenues - $totalExpenses;

        return $schema
            ->components([
                Section::make('فلترة التقارير')
                    ->description('اختر الدورة أو الفترة الزمنية لتخصيص البيانات.')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('cycle_id')
                                    ->label('الدورة')
                                    ->options(Cycle::pluck('name', 'id'))
                                    ->placeholder('كل الدورات')
                                    ->reactive(),
                                DatePicker::make('date_from')
                                    ->label('من تاريخ')
                                    ->reactive(),
                                DatePicker::make('date_to')
                                    ->label('إلى تاريخ')
                                    ->reactive(),
                            ]),
                    ]),

                Grid::make(3)
                    ->schema([
                        Section::make('إجمالي الإيرادات')
                            ->schema([
                                Text::make(fn() => number_format($totalRevenues, 2) . ' ج.م')
                                    ->size('xl')
                                    ->weight('bold')
                                    ->color('success'),
                                Text::make('المحصل: ' . number_format($collectedRevenues, 2) . ' ج.م'),
                                Text::make('المتبقي: ' . number_format($remainingRevenues, 2) . ' ج.م'),
                            ])->columnSpan(1),

                        Section::make('إجمالي المصروفات')
                            ->schema([
                                Text::make(fn() => number_format($totalExpenses, 2) . ' ج.م')
                                    ->size('xl')
                                    ->weight('bold')
                                    ->color('danger'),
                                Text::make('شاملة العلف والدواء والطاقة والمصاريف النثرية.'),
                            ])->columnSpan(1),

                        Section::make('صافي الربح التقديري')
                            ->schema([
                                Text::make(fn() => number_format($netProfit, 2) . ' ج.م')
                                    ->size('xl')
                                    ->weight('bold')
                                    ->color($netProfit >= 0 ? 'success' : 'danger'),
                                Text::make('الإيرادات الكلية - المصروفات الكلية.'),
                            ])->columnSpan(1),
                    ]),

                Grid::make(2)
                    ->schema([
                        Section::make('تفاصيل المصروفات')
                            ->schema([
                                Text::make('العلف: ' . number_format($feedExp, 2) . ' ج.م'),
                                Text::make('الأدوية والتحصينات: ' . number_format($medExp, 2) . ' ج.م'),
                                Text::make('الغاز والطاقة: ' . number_format($gasExp, 2) . ' ج.م'),
                                Text::make('النشارة: ' . number_format($sawdustExp, 2) . ' ج.م'),
                                Text::make('مصاريف نثرية: ' . number_format($miscExp, 2) . ' ج.م'),
                            ]),

                        Section::make('مؤشرات الأداء')
                            ->schema([
                                Text::make(function() use ($salesQuery) {
                                    $birdsSold = $salesQuery->sum('bird_count');
                                    return 'إجمالي الطيور المباعة: ' . number_format($birdsSold);
                                }),
                                Text::make(function() {
                                    $totalMortality = $this->applyFilters(\App\Models\MortalityLog::query(), 'log_date')->sum('count');
                                    return 'إجمالي النافق: ' . number_format($totalMortality);
                                }),
                                Text::make(fn() => 'عدد عمليات البيع: ' . $salesQuery->count()),
                            ]),
                    ]),

                Section::make('مستحقات المشترين (فلوس لينا)')
                    ->description('قائمة المشترين الذين لم يسددوا كامل المبالغ بعد.')
                    ->schema([
                        Grid::make(1)
                            ->schema(
                                Sale::where('remaining', '>', 0)
                                    ->with(['buyer', 'cycle'])
                                    ->get()
                                    ->map(fn($sale) => 
                                        Text::make(($sale->buyer?->name ?? 'مشتري نقدي') . " - دورة (" . ($sale->cycle?->name ?? '-') . ") : " . number_format($sale->remaining, 2) . ' ج.م')
                                    )->toArray()
                            )
                    ]),

                Section::make('ديون الموردين (فلوس علينا)')
                    ->description('قائمة الفواتير المتبقي منها مبالغ للموردين.')
                    ->schema([
                        Grid::make(1)
                            ->schema(array_merge(
                                FeedPurchase::where('remaining', '>', 0)->with('cycle')->get()->map(fn($p) => 
                                    Text::make('علف: ' . ($p->supplier_name ?: 'مورد') . " (" . $p->cycle->name . ") : " . number_format($p->remaining, 2) . ' ج.م')
                                )->toArray(),
                                MedicineInvoice::where('remaining', '>', 0)->with('cycle')->get()->map(fn($p) => 
                                    Text::make('دواء: ' . ($p->pharmacy_name ?: 'صيدلية') . " (" . $p->cycle->name . ") : " . number_format($p->remaining, 2) . ' ج.م')
                                )->toArray(),
                                GasInvoice::where('remaining', '>', 0)->with('cycle')->get()->map(fn($p) => 
                                    Text::make('غاز: ' . ($p->supplier_name ?: 'مورد') . " (" . $p->cycle->name . ") : " . number_format($p->remaining, 2) . ' ج.م')
                                )->toArray(),
                                SawdustPurchase::where('remaining', '>', 0)->with('cycle')->get()->map(fn($p) => 
                                    Text::make('نشارة: ' . ($p->supplier_name ?: 'مورد') . " (" . $p->cycle->name . ") : " . number_format($p->remaining, 2) . ' ج.م')
                                )->toArray()
                            ))
                    ]),
            ]);
    }
}

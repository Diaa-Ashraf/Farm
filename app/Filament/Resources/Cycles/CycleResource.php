<?php

namespace App\Filament\Resources\Cycles;

use App\Filament\Resources\Cycles\Pages\CreateCycle;
use App\Filament\Resources\Cycles\Pages\EditCycle;
use App\Filament\Resources\Cycles\Pages\ListCycles;
use App\Filament\Resources\Cycles\Pages\ViewCycle;
use App\Filament\Resources\Cycles\Schemas\CycleForm;
use App\Filament\Resources\Cycles\Tables\CyclesTable;
use App\Models\Cycle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use UnitEnum;
use BackedEnum;

// ✅ الصح
use Filament\Infolists\Components\TextEntry;

class CycleResource extends Resource
{
    protected static ?string $model = Cycle::class;

    protected static ?string $modelLabel = 'دورة';
    protected static ?string $pluralModelLabel = 'الدورات';
    protected static ?string $navigationLabel = 'الدورات';
protected static string|UnitEnum|null $navigationGroup = 'إدارة المزرعة';
protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CycleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ========================
                // بيانات الدورة
                // ========================
                Section::make('معلومات الدورة')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name')->label('اسم الدورة'),

                                TextEntry::make('farm.name')
                                    ->label('المزرعة'),

                                TextEntry::make('start_date')
                                    ->label('تاريخ البداية')
                                    ->date(),

                                TextEntry::make('end_date')
                                    ->label('تاريخ النهاية')
                                    ->date()
                                    ->placeholder('لم تنتهِ بعد'),

                                TextEntry::make('initial_count')
                                    ->label('عدد الكتاكيت الأولي'),

                                TextEntry::make('status')
                                    ->label('الحالة')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'active' => 'success',
                                        'closed' => 'danger',
                                    })
                                    ->formatStateUsing(fn(string $state): string => match ($state) {
                                        'active' => 'نشطة',
                                        'closed' => 'مغلقة',
                                    }),
                            ]),

                        TextEntry::make('notes')
                            ->label('ملاحظات')
                            ->columnSpanFull(),
                    ]),

                // ========================
                // تقارير الإغلاق
                // ========================
                Section::make('تقارير الإغلاق')
                    ->schema([

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('closing_date')
                                    ->label('تاريخ الإغلاق')
                                    ->date()
                                    ->state(fn($record) => $record->cycleClosing?->closing_date),

                                TextEntry::make('cycle_days')
                                    ->label('أيام الدورة')
                                    ->state(fn($record) => $record->cycleClosing?->cycle_days),

                                TextEntry::make('initial_count_closing')
                                    ->label('عدد الكتاكيت (إغلاق)')
                                    ->state(fn($record) => $record->cycleClosing?->initial_count),

                                TextEntry::make('mortality_count')
                                    ->label('عدد الوفيات')
                                    ->state(fn($record) => $record->cycleClosing?->mortality_count),

                                TextEntry::make('sold_count')
                                    ->label('عدد الطيور المباعة')
                                    ->state(fn($record) => $record->cycleClosing?->sold_count),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextEntry::make('chick_cost')
                                    ->label('تكلفة الكتاكيت')
                                    ->money('EGP')
                                    ->state(fn($record) => $record->cycleClosing?->chick_cost),

                                TextEntry::make('feed_cost')
                                    ->label('تكلفة العلف')
                                    ->money('EGP')
                                    ->state(fn($record) => $record->cycleClosing?->feed_cost),

                                TextEntry::make('medicine_cost')
                                    ->label('تكلفة الأدوية')
                                    ->money('EGP')
                                    ->state(fn($record) => $record->cycleClosing?->medicine_cost),

                                TextEntry::make('gas_cost')
                                    ->label('تكلفة الغاز')
                                    ->money('EGP')
                                    ->state(fn($record) => $record->cycleClosing?->gas_cost),

                                TextEntry::make('sawdust_cost')
                                    ->label('تكلفة نشارة الخشب')
                                    ->money('EGP')
                                    ->state(fn($record) => $record->cycleClosing?->sawdust_cost),

                                TextEntry::make('worker_cost')
                                    ->label('تكلفة العمال')
                                    ->money('EGP')
                                    ->state(fn($record) => $record->cycleClosing?->worker_cost),

                                TextEntry::make('misc_cost')
                                    ->label('مصروفات متنوعة')
                                    ->money('EGP')
                                    ->state(fn($record) => $record->cycleClosing?->misc_cost),

                                TextEntry::make('total_expenses')
                                    ->label('إجمالي المصروفات')
                                    ->money('EGP')
                                    ->state(fn($record) => $record->cycleClosing?->total_expenses),

                                TextEntry::make('total_revenue')
                                    ->label('إجمالي الإيرادات')
                                    ->money('EGP')
                                    ->state(fn($record) => $record->cycleClosing?->total_revenue),

                                TextEntry::make('net_profit')
                                    ->label('صافي الربح')
                                    ->money('EGP')
                                    ->color(fn($record) => ($record->cycleClosing?->net_profit ?? 0) >= 0 ? 'success' : 'danger')
                                    ->state(fn($record) => $record->cycleClosing?->net_profit),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('fcr')
                                    ->label('FCR')
                                    ->state(fn($record) => $record->cycleClosing?->fcr),

                                TextEntry::make('avg_weight_kg')
                                    ->label('متوسط الوزن')
                                    ->state(fn($record) => $record->cycleClosing?->avg_weight_kg),

                                TextEntry::make('mortality_rate')
                                    ->label('معدل الوفيات')
                                    ->state(fn($record) => $record->cycleClosing?->mortality_rate),

                                TextEntry::make('profit_per_kg')
                                    ->label('الربح لكل كيلو')
                                    ->money('EGP')
                                    ->state(fn($record) => $record->cycleClosing?->profit_per_kg),

                                TextEntry::make('result')
                                    ->label('النتيجة')
                                    ->badge()
                                    ->color(fn($record) => match ($record->cycleClosing?->result) {
                                        'profit' => 'success',
                                        'loss' => 'danger',
                                        'break_even' => 'warning',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn($record) => match ($record->cycleClosing?->result) {
                                        'profit' => 'ربح',
                                        'loss' => 'خسارة',
                                        'break_even' => 'تعادل',
                                        default => '-',
                                    })
                                    ->state(fn($record) => $record->cycleClosing?->result),
                            ]),
                    ])
                    ->visible(fn($record) => $record->status === 'closed'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return CyclesTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with([
            'feedPurchases',
            'medicineInvoices',
            'gasInvoices',
            'workerCycles',
            'sales',
            'sawdustPurchases',
            'miscellaneousExpenses',
            'mortalityLogs',
            'cycleClosing',
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCycles::route('/'),
            'create' => CreateCycle::route('/create'),
            'view' => ViewCycle::route('/{record}'),
            'edit' => EditCycle::route('/{record}/edit'),
        ];
    }
}

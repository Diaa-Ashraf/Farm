<?php

namespace App\Filament\Widgets;

use App\Models\Cycle;
use App\Models\Farm;
use App\Models\Sale;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class FarmStatusWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'نظرة عامة على المزرعة';

    protected ?string $description = 'ملخص سريع لحالة المزرعة والأداء المالي.';

    protected int | string | array $columnSpan = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('المزارع', Farm::count())
                ->icon(Heroicon::BuildingOffice)
                ->description('عدد المزارع المسجلة'),

            Stat::make('الدورات المفتوحة', Cycle::where('status', 'active')->count())
                ->icon(Heroicon::Play)
                ->description('عدد الدورات الجارية'),

            Stat::make('إجمالي المبيعات', number_format(Sale::sum('total'), 2))
                ->icon(Heroicon::ShoppingCart)
                ->description('إجمالي قيمة المبيعات'),
        ];
    }
}

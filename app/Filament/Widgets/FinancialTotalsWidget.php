<?php

namespace App\Filament\Widgets;

use App\Models\FinancialRecord;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinancialTotalsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'موجز مالي';

    protected ?string $description = 'أرقام مالية سريعة عن الذمم والمستحقات الحالية.';

    protected int | string | array $columnSpan = 2;

    protected function getStats(): array
    {
        $payable = FinancialRecord::where('type', 'payable')->where('status', 'active')->sum('remaining');
        $receivable = FinancialRecord::where('type', 'receivable')->where('status', 'active')->sum('remaining');

        return [
            Stat::make('الديون الحالية', number_format($payable, 2))
                ->icon(Heroicon::Banknotes)
                ->description('القيمة المتبقية للدفع حالياً'),

            Stat::make('المستحقات الحالية', number_format($receivable, 2))
                ->icon(Heroicon::CurrencyDollar)
                ->description('القيمة المستحقة من المبيعات'),

            Stat::make('إجمالي السجلات المالية', FinancialRecord::count())
                ->icon(Heroicon::DocumentChartBar)
                ->description('عدد السجلات المالية المسجلة'),
        ];
    }
}

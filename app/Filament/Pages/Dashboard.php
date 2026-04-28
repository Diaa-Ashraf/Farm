<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\FarmStatusWidget;
use App\Filament\Widgets\FinancialTotalsWidget;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'لوحة التحكم';
    
    protected static ?string $navigationLabel = 'الرئيسية';
    
    protected static string | BackedEnum | null $navigationIcon = Heroicon::Home;

    public function getWidgets(): array
    {
        return [
            FarmStatusWidget::class,
            FinancialTotalsWidget::class,
        ];
    }
}

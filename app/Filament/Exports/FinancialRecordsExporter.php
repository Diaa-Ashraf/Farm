<?php

namespace App\Filament\Exports;

use App\Models\FinancialRecord;
use Filament\Actions\Exports\Export;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;

class FinancialRecordsExporter extends Exporter
{
    protected static ?string $model = FinancialRecord::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('المعرف'),
            ExportColumn::make('cycle.name')->label('الدورة'),
            ExportColumn::make('party_name')->label('اسم الطرف'),
            ExportColumn::make('party_type')->label('نوع الطرف'),
            ExportColumn::make('type')->label('النوع'),
            ExportColumn::make('original_amount')->label('المبلغ الأصلي'),
            ExportColumn::make('paid_amount')->label('المدفوع'),
            ExportColumn::make('remaining')->label('المتبقي'),
            ExportColumn::make('status')->label('الحالة'),
        ];
    }

public static function getCompletedNotificationBody(\Filament\Actions\Exports\Models\Export $export): string
    {
        return 'تم تصدير السجلات المالية بنجاح.';
    }
}

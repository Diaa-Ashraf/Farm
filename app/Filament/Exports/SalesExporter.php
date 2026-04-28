<?php

namespace App\Filament\Exports;

use App\Models\Sale;
use Filament\Actions\Exports\Export;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;

class SalesExporter extends Exporter
{
    protected static ?string $model = Sale::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('المعرف'),
            ExportColumn::make('cycle.name')->label('الدورة'),
            ExportColumn::make('buyer.name')->label('المشتري'),
            ExportColumn::make('sale_date')->label('تاريخ البيع'),
            ExportColumn::make('truck_number')->label('رقم السيارة'),
            ExportColumn::make('total_weight_kg')->label('الوزن الكلي'),
            ExportColumn::make('bird_count')->label('عدد الطيور'),
            ExportColumn::make('price_per_kg')->label('سعر الكيلو'),
            ExportColumn::make('total')->label('الإجمالي'),
            ExportColumn::make('paid_amount')->label('المدفوع'),
            ExportColumn::make('remaining')->label('المتبقي'),
            ExportColumn::make('payment_status')->label('حالة الدفع'),
        ];
    }

public static function getCompletedNotificationBody(\Filament\Actions\Exports\Models\Export $export): string
{
    return 'تم تصدير بيانات المبيعات بنجاح.';
}
}

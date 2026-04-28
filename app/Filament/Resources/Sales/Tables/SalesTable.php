<?php

namespace App\Filament\Resources\Sales\Tables;

use App\Filament\Exports\SalesExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cycle.name')
                    ->label('الدورة')
                    ->searchable(),
                TextColumn::make('buyer.name')
                    ->label('المشتري')
                    ->searchable(),
                TextColumn::make('sale_date')
                    ->label('تاريخ البيع')
                    ->date(),
                TextColumn::make('truck_number')
                    ->label('رقم العربية'),
                TextColumn::make('total_weight_kg')
                    ->label('الوزن الكلي'),
                TextColumn::make('bird_count')
                    ->label('عدد الطيور'),
                TextColumn::make('price_per_kg')
                    ->label('سعر الكيلو'),
                TextColumn::make('total')
                    ->label('الإجمالي'),
                TextColumn::make('paid_amount')
                    ->label('المدفوع'),
                TextColumn::make('remaining')
                    ->label('المتبقي'),
                TextColumn::make('payment_status')
                    ->label('حالة الدفع')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                ExportBulkAction::make('export')
                    ->label('تصدير')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(SalesExporter::class),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

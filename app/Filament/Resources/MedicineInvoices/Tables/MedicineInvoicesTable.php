<?php

namespace App\Filament\Resources\MedicineInvoices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MedicineInvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cycle.name')
                    ->label('الدورة')
                    ->searchable(),
                TextColumn::make('pharmacy_name')
                    ->label('اسم الصيدلية')
                    ->searchable(),
                TextColumn::make('invoice_date')
                    ->label('تاريخ الفاتورة')
                    ->date(),
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
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

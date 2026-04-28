<?php

namespace App\Filament\Resources\SawdustPurchases\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SawdustPurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cycle.name')
                    ->label('الدورة')
                    ->searchable(),
                TextColumn::make('supplier_name')
                    ->label('اسم المورد')
                    ->searchable(),
                TextColumn::make('purchase_date')
                    ->label('تاريخ الشراء')
                    ->date(),
                TextColumn::make('quantity_tons')
                    ->label('الكمية بالطن'),
                TextColumn::make('price_per_ton')
                    ->label('سعر الطن'),
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

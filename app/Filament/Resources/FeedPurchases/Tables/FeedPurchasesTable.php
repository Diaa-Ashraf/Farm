<?php

namespace App\Filament\Resources\FeedPurchases\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FeedPurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cycle.name')
                    ->label('الدورة')
                    ->searchable(),
                TextColumn::make('feedType.name')
                    ->label('نوع العلف')
                    ->searchable(),
                TextColumn::make('supplier_name')
                    ->label('اسم المورد')
                    ->searchable(),
                TextColumn::make('purchase_date')
                    ->label('تاريخ الشراء')
                    ->date(),
                TextColumn::make('unit')
                    ->label('الوحدة')
                    ->badge(),
                TextColumn::make('quantity')
                    ->label('الكمية'),
                TextColumn::make('unit_price')
                    ->label('سعر الوحدة'),
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

<?php

namespace App\Filament\Resources\MiscellaneousExpenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MiscellaneousExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cycle.name')
                    ->label('الدورة')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('الوصف')
                    ->searchable(),
                TextColumn::make('paid_to')
                    ->label('المدفوع لـ')
                    ->searchable(),
                TextColumn::make('expense_date')
                    ->label('التاريخ')
                    ->date(),
                TextColumn::make('amount')
                    ->label('المبلغ'),
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

<?php

namespace App\Filament\Resources\CycleClosings\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CycleClosingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cycle.name')
                    ->label('الدورة')
                    ->searchable(),
                TextColumn::make('closing_date')
                    ->label('تاريخ التقفيل')
                    ->date(),
                TextColumn::make('cycle_days')
                    ->label('أيام الدورة'),
                TextColumn::make('total_expenses')
                    ->label('إجمالي المصاريف'),
                TextColumn::make('total_revenue')
                    ->label('إجمالي الإيرادات'),
                TextColumn::make('net_profit')
                    ->label('صافي الربح'),
                TextColumn::make('result')
                    ->label('النتيجة')
                    ->badge(),
                TextColumn::make('fcr')
                    ->label('FCR'),
                TextColumn::make('avg_weight_kg')
                    ->label('متوسط الوزن'),
                TextColumn::make('mortality_rate')
                    ->label('معدل النفوق'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}

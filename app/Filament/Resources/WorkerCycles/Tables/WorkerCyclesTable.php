<?php

namespace App\Filament\Resources\WorkerCycles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkerCyclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cycle.name')
                    ->label('الدورة')
                    ->searchable(),
                TextColumn::make('worker.name')
                    ->label('العامل')
                    ->searchable(),
                TextColumn::make('work_start_date')
                    ->label('بداية العمل')
                    ->date(),
                TextColumn::make('work_end_date')
                    ->label('نهاية العمل')
                    ->date(),
                TextColumn::make('gross_wage')
                    ->label('الأجر الإجمالي'),
                TextColumn::make('net_wage')
                    ->label('الصافي'),
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

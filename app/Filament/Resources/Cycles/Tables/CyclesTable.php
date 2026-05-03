<?php

namespace App\Filament\Resources\Cycles\Tables;

use App\Filament\Resources\Cycles\Actions\CloseCycleAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CyclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('farm.name')
                    ->label('المزرعة')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('اسم الدورة')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label('تاريخ البداية')
                    ->date(),
                TextColumn::make('end_date')
                    ->label('تاريخ النهاية')
                    ->date(),
                TextColumn::make('initial_count')
                    ->label('عدد الكتاكيت'),
                TextColumn::make('chick_total')
                    ->label('تكلفة الكتاكيت'),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                CloseCycleAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

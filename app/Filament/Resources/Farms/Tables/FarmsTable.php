<?php

namespace App\Filament\Resources\Farms\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FarmsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم المزرعة')
                    ->searchable(),
                TextColumn::make('location')
                    ->label('الموقع')
                    ->searchable(),
                TextColumn::make('capacity')
                    ->label('الطاقة'),
                TextColumn::make('activeCycle.name')
                    ->label('الدورة النشطة')
                    ->placeholder('لا توجد'),
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

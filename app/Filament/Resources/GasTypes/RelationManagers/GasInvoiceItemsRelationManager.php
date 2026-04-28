<?php

namespace App\Filament\Resources\GasTypes\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GasInvoiceItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'gasInvoiceItems';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('gasInvoice.invoice_date')
            ->columns([
                TextColumn::make('gasInvoice.invoice_date')
                    ->label('تاريخ الفاتورة')
                    ->date(),
                TextColumn::make('gasInvoice.cycle.name')
                    ->label('الدورة'),
                TextColumn::make('quantity')
                    ->label('الكمية'),
                TextColumn::make('price_per_unit')
                    ->label('السعر للوحدة'),
                TextColumn::make('total')
                    ->label('الإجمالي'),
            ])
            ->filters([
                //
            ]);
    }
}

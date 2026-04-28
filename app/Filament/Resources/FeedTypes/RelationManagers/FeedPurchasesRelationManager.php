<?php

namespace App\Filament\Resources\FeedTypes\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FeedPurchasesRelationManager extends RelationManager
{
    protected static string $relationship = 'feedPurchases';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('cycle_id')
                            ->label('الدورة')
                            ->relationship('cycle', 'name')
                            ->preload()
                            ->required(),
                        DatePicker::make('purchase_date')
                            ->label('تاريخ الشراء')
                            ->required(),
                        TextInput::make('quantity_kg')
                            ->label('الكمية (كجم)')
                            ->numeric()
                            ->required(),
                        TextInput::make('price_per_kg')
                            ->label('السعر للكيلو')
                            ->numeric()
                            ->required(),
                        TextInput::make('total')
                            ->label('الإجمالي')
                            ->numeric()
                            ->required(),
                        TextInput::make('paid_amount')
                            ->label('المبلغ المدفوع')
                            ->numeric()
                            ->default(0),
                        TextInput::make('remaining')
                            ->label('المتبقي')
                            ->numeric()
                            ->default(0),
                        Select::make('payment_status')
                            ->label('حالة الدفع')
                            ->options([
                                'paid' => 'مدفوع',
                                'partial' => 'جزئي',
                                'unpaid' => 'غير مدفوع',
                            ])
                            ->default('unpaid'),
                        Textarea::make('notes')
                            ->label('ملاحظات')
                            ->rows(3),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('quantity_kg')
            ->columns([
                TextColumn::make('purchase_date')
                    ->label('تاريخ الشراء')
                    ->date(),
                TextColumn::make('quantity_kg')
                    ->label('الكمية'),
                TextColumn::make('price_per_kg')
                    ->label('السعر للكيلو'),
                TextColumn::make('total')
                    ->label('الإجمالي'),
                TextColumn::make('payment_status')
                    ->label('حالة الدفع')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'partial' => 'warning',
                        'unpaid' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'paid' => 'مدفوع',
                        'partial' => 'جزئي',
                        'unpaid' => 'غير مدفوع',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

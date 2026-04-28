<?php

namespace App\Filament\Resources\Buyers\RelationManagers;

use Filament\Actions\CreateAction as ActionsCreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

// ✅ Actions Imports
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class SalesRelationManager extends RelationManager
{
    protected static string $relationship = 'sales';

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

                        DatePicker::make('sale_date')
                            ->label('تاريخ البيع')
                            ->required(),

                        TextInput::make('truck_number')
                            ->label('رقم الشاحنة'),

                        // ✅ الوزن
                        TextInput::make('total_weight_kg')
                            ->label('الوزن الكلي (كجم)')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(
                                fn($state, $set, $get) =>
                                $set('total', ($get('total_weight_kg') ?? 0) * ($get('price_per_kg') ?? 0))
                            ),

                        // ✅ السعر
                        TextInput::make('price_per_kg')
                            ->label('السعر للكيلو')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(
                                fn($state, $set, $get) =>
                                $set('total', ($get('total_weight_kg') ?? 0) * ($get('price_per_kg') ?? 0))
                            ),

                        // ✅ الإجمالي (محسوب)
                        TextInput::make('total')
                            ->label('الإجمالي')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('bird_count')
                            ->label('عدد الطيور')
                            ->numeric(),

                        // ✅ المدفوع
                        TextInput::make('paid_amount')
                            ->label('المبلغ المدفوع')
                            ->numeric()
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $total = $get('total') ?? 0;
                                $paid = $state ?? 0;

                                $remaining = $total - $paid;
                                $set('remaining', $remaining);

                                // تحديث حالة الدفع
                                if ($paid >= $total && $total > 0) {
                                    $set('payment_status', 'paid');
                                } elseif ($paid > 0) {
                                    $set('payment_status', 'partial');
                                } else {
                                    $set('payment_status', 'unpaid');
                                }
                            }),

                        // ✅ المتبقي (محسوب)
                        TextInput::make('remaining')
                            ->label('المتبقي')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                        // ✅ حالة الدفع
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
            ->recordTitleAttribute('truck_number')
            ->columns([
                TextColumn::make('sale_date')
                    ->label('تاريخ البيع')
                    ->date(),

                TextColumn::make('truck_number')
                    ->label('رقم الشاحنة'),

                TextColumn::make('total_weight_kg')
                    ->label('الوزن الكلي'),

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

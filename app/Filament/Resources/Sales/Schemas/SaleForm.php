<?php

namespace App\Filament\Resources\Sales\Schemas;

use App\Models\Cycle;
use App\Models\MortalityLog;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('cycle_id')
                            ->label('الدورة')
                            ->relationship('cycle', 'name')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                if (!$state) return;
                                
                                $cycle = Cycle::find($state);
                                if (!$cycle) return;
                                
                                $initialCount = $cycle->initial_count ?? 0;
                                $mortalityCount = MortalityLog::where('cycle_id', $state)->sum('count');
                                
                                $set('bird_count', max($initialCount - $mortalityCount, 0));
                            }),
                        Select::make('buyer_id')
                            ->label('المشتري')
                            ->relationship('buyer', 'name')
                            ->preload()
                            ->searchable(),
                        DatePicker::make('sale_date')
                            ->label('تاريخ البيع')
                            ->required(),
                        TextInput::make('truck_number')
                            ->label('رقم العربية')
                            ->maxLength(255),
                        TextInput::make('total_weight_kg')
                            ->label('الوزن الكلي (كجم)')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $weight = floatval($state ?? 0);
                                $price = floatval($get('price_per_kg') ?? 0);
                                $total = $weight * $price;
                                $set('total', $total);
                                
                                $paid = floatval($get('paid_amount') ?? 0);
                                $set('remaining', max($total - $paid, 0));
                            }),
                        TextInput::make('bird_count')
                            ->label('عدد الطيور')
                            ->numeric(),
                        TextInput::make('price_per_kg')
                            ->label('سعر الكيلو')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $price = floatval($state ?? 0);
                                $weight = floatval($get('total_weight_kg') ?? 0);
                                $total = $weight * $price;
                                $set('total', $total);
                                
                                $paid = floatval($get('paid_amount') ?? 0);
                                $set('remaining', max($total - $paid, 0));
                            }),
                        TextInput::make('total')
                            ->label('الإجمالي')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0),
                        TextInput::make('paid_amount')
                            ->label('المدفوع')
                            ->numeric()
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $total = floatval($get('total') ?? 0);
                                $paid = floatval($state ?? 0);
                                $set('remaining', max($total - $paid, 0));
                            }),
                        TextInput::make('remaining')
                            ->label('المتبقي')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0),
                        Select::make('payment_status')
                            ->label('حالة الدفع')
                            ->options([
                                'paid' => 'مدفوعة',
                                'partial' => 'جزئي',
                                'unpaid' => 'غير مدفوعة',
                            ])
                            ->required(),
                        Textarea::make('notes')
                            ->label('ملاحظات')
                            ->rows(3),
                    ]),
            ]);
    }
}

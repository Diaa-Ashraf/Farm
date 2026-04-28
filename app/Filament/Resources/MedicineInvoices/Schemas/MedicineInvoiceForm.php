<?php

namespace App\Filament\Resources\MedicineInvoices\Schemas;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class MedicineInvoiceForm
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
                            ->required(),
                        TextInput::make('pharmacy_name')
                            ->label('اسم الصيدلية')
                            ->maxLength(255),
                        TextInput::make('pharmacy_phone')
                            ->label('هاتف الصيدلية')
                            ->maxLength(255),
                        DatePicker::make('invoice_date')
                            ->label('تاريخ الفاتورة')
                            ->required(),

                        \Filament\Forms\Components\Repeater::make('items')
                            ->label('الأصناف')
                            ->relationship('items')
                            ->schema([
                                TextInput::make('medicine_name')
                                    ->label('اسم الصنف')
                                    ->required(),
                                TextInput::make('quantity')
                                    ->label('الكمية')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $quantity = floatval($state ?? 0);
                                        $unitPrice = floatval($get('unit_price') ?? 0);
                                        $set('total', $quantity * $unitPrice);
                                        
                                        // تحديث الإجمالي الكلي عند تغيير كمية أي صنف
                                        $items = $get('../../items') ?? [];
                                        $grandTotal = 0;
                                        foreach ($items as $item) {
                                            $grandTotal += floatval($item['total'] ?? 0);
                                        }
                                        $set('../../total', $grandTotal);
                                        
                                        $paid = floatval($get('../../paid_amount') ?? 0);
                                        $set('../../remaining', max($grandTotal - $paid, 0));
                                    }),
                                TextInput::make('unit_price')
                                    ->label('سعر الوحدة')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $unitPrice = floatval($state ?? 0);
                                        $quantity = floatval($get('quantity') ?? 0);
                                        $set('total', $quantity * $unitPrice);
                                        
                                        // تحديث الإجمالي الكلي عند تغيير سعر أي صنف
                                        $items = $get('../../items') ?? [];
                                        $grandTotal = 0;
                                        foreach ($items as $item) {
                                            $grandTotal += floatval($item['total'] ?? 0);
                                        }
                                        $set('../../total', $grandTotal);
                                        
                                        $paid = floatval($get('../../paid_amount') ?? 0);
                                        $set('../../remaining', max($grandTotal - $paid, 0));
                                    }),
                                TextInput::make('total')
                                    ->label('الإجمالي')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns(4)
                            ->reactive()
                            ->afterStateUpdated(function ($set, $get) {
                                $items = $get('items') ?? [];
                                $total = 0;
                                foreach ($items as $item) {
                                    $total += floatval($item['total'] ?? 0);
                                }
                                $set('total', $total);
                                
                                $paidAmount = floatval($get('paid_amount') ?? 0);
                                $remaining = max($total - $paidAmount, 0);
                                $set('remaining', $remaining);
                            }),

                        TextInput::make('total')
                            ->label('الإجمالي الكلي')
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

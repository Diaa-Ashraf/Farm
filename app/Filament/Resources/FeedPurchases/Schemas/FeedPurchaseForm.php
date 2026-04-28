<?php

namespace App\Filament\Resources\FeedPurchases\Schemas;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class FeedPurchaseForm
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
                        Select::make('feed_type_id')
                            ->label('نوع العلف')
                            ->relationship('feedType', 'name')
                            ->preload()
                            ->searchable()
                            ->required(),
                        TextInput::make('supplier_name')
                            ->label('اسم المورد')
                            ->maxLength(255),
                        TextInput::make('supplier_phone')
                            ->label('هاتف المورد')
                            ->maxLength(255),
                        Select::make('unit')
                            ->label('الوحدة')
                            ->options([
                                'ton' => 'طن',
                                'bag' => 'شكارة',
                            ])
                            ->required(),
                        TextInput::make('quantity')
                            ->label('الكمية')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($get, $set) => self::updateTotals($get, $set)),
                        TextInput::make('unit_price')
                            ->label('سعر الوحدة')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($get, $set) => self::updateTotals($get, $set)),
                        TextInput::make('total')
                            ->label('الإجمالي')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0),
                        DatePicker::make('purchase_date')
                            ->label('تاريخ الشراء')
                            ->required(),
                        TextInput::make('paid_amount')
                            ->label('المدفوع')
                            ->numeric()
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(fn ($get, $set) => self::updateTotals($get, $set)),
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

    public static function updateTotals($get, $set)
    {
        $quantity = floatval($get('quantity') ?? 0);
        $unitPrice = floatval($get('unit_price') ?? 0);
        $total = $quantity * $unitPrice;
        
        $set('total', $total);
        
        $paid = floatval($get('paid_amount') ?? 0);
        $remaining = max($total - $paid, 0);
        $set('remaining', $remaining);

        if ($remaining <= 0 && $total > 0) {
            $set('payment_status', 'paid');
        } elseif ($paid > 0) {
            $set('payment_status', 'partial');
        } else {
            $set('payment_status', 'unpaid');
        }
    }
}

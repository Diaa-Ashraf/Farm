<?php

namespace App\Filament\Resources\FinancialRecords\Schemas;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class FinancialRecordForm
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
                            ->disabled(),
                        TextInput::make('party_name')
                            ->label('اسم الطرف')
                            ->disabled(),
                        TextInput::make('party_phone')
                            ->label('هاتف الطرف')
                            ->disabled(),
                        TextInput::make('party_type')
                            ->label('نوع الطرف')
                            ->disabled(),
                        Select::make('type')
                            ->label('النوع')
                            ->options([
                                'payable' => 'علينا',
                                'receivable' => 'لينا',
                            ])
                            ->disabled(),
                        TextInput::make('original_amount')
                            ->label('المبلغ الأصلي')
                            ->disabled(),
                        TextInput::make('paid_amount')
                            ->label('المدفوع')
                            ->disabled(),
                        TextInput::make('remaining')
                            ->label('المتبقي')
                            ->disabled(),
                        DatePicker::make('due_date')
                            ->label('تاريخ الاستحقاق')
                            ->disabled(),
                        Select::make('status')
                            ->label('الحالة')
                            ->options([
                                'active' => 'نشط',
                                'settled' => 'مُسوى',
                            ])
                            ->disabled(),
                        Textarea::make('notes')
                            ->label('ملاحظات')
                            ->disabled(),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\MiscellaneousExpenses\Schemas;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class MiscellaneousExpenseForm
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
                        Select::make('worker_id')
                            ->label('العامل (اختياري)')
                            ->relationship('worker', 'name')
                            ->placeholder('اختر عاملاً في حال كانت سلفة')
                            ->preload()
                            ->searchable()
                            ->helperText('إذا اخترت عاملاً، سيتم اعتبار هذا المصروف سلفة على راتبه.'),
                        TextInput::make('description')
                            ->label('الوصف')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('paid_to')
                            ->label('المدفوع لـ')
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('الهاتف')
                            ->maxLength(255),
                        TextInput::make('amount')
                            ->label('المبلغ')
                            ->numeric()
                            ->required(),
                        DatePicker::make('expense_date')
                            ->label('تاريخ المصروف')
                            ->required(),
                        Select::make('payment_status')
                            ->label('حالة الدفع')
                            ->options([
                                'paid' => 'مدفوع',
                                'unpaid' => 'غير مدفوع',
                            ])
                            ->required(),
                        Textarea::make('notes')
                            ->label('ملاحظات')
                            ->rows(3),
                    ]),
            ]);
    }
}

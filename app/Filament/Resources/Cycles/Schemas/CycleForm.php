<?php

namespace App\Filament\Resources\Cycles\Schemas;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class CycleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('farm_id')
                            ->label('المزرعة')
                            ->relationship('farm', 'name')
                            ->searchable()
                            ->required()
                            ->preload(),
                        TextInput::make('name')
                            ->label('اسم الدورة')
                            ->maxLength(255),
                        DatePicker::make('start_date')
                            ->label('تاريخ البداية')
                            ->required(),
                        TextInput::make('initial_count')
                            ->label('عدد الكتاكيت')
                            ->numeric()
                            ->required(),
                        TextInput::make('chick_price')
                            ->label('سعر الكتكوت')
                            ->numeric()
                            ->required(),
                        TextInput::make('chick_total')
                            ->label('تكلفة الكتاكيت')
                            ->numeric()
                            ->disabled()
                            ->dehydrateStateUsing(fn($state, $get) => $get('initial_count') * $get('chick_price'))
                            ->default(0),
                        Select::make('status')
                            ->label('الحالة')
                            ->options([
                                'active' => 'نشطة',
                                'closed' => 'مغلقة',
                            ])
                            ->required(),
                        Textarea::make('notes')
                            ->label('ملاحظات')
                            ->rows(3),
                    ]),
            ]);
    }
}

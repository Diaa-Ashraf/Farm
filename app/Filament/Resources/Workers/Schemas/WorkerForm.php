<?php

namespace App\Filament\Resources\Workers\Schemas;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class WorkerForm
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
                            ->preload()
                            ->searchable()
                            ->required(),
                        TextInput::make('name')
                            ->label('الاسم')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('الهاتف')
                            ->maxLength(255),
                        TextInput::make('daily_wage')
                            ->label('الأجر اليومي')
                            ->numeric()
                            ->required(),
                        Select::make('status')
                            ->label('الحالة')
                            ->options([
                                'active' => 'نشط',
                                'inactive' => 'غير نشط',
                            ])
                            ->required(),
                        Textarea::make('notes')
                            ->label('ملاحظات')
                            ->rows(3),
                    ]),
            ]);
    }
}

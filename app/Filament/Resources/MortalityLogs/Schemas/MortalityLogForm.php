<?php

namespace App\Filament\Resources\MortalityLogs\Schemas;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class MortalityLogForm
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
                        DatePicker::make('log_date')
                            ->label('تاريخ النافق')
                            ->required(),
                        TextInput::make('count')
                            ->label('عدد الطيور النافقة')
                            ->numeric()
                            ->required(),
                        Textarea::make('reason')
                            ->label('السبب')
                            ->rows(3),
                    ]),
            ]);
    }
}

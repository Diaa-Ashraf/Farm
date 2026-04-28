<?php

namespace App\Filament\Resources\GasTypes\Schemas;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class GasTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم نوع الأنبوب')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('default_price')
                            ->label('السعر الافتراضي')
                            ->numeric()
                            ->required()
                            ->default(0),
                    ]),
            ]);
    }
}

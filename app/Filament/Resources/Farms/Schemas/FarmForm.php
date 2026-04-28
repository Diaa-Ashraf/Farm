<?php

namespace App\Filament\Resources\Farms\Schemas;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class FarmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم المزرعة')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('location')
                            ->label('الموقع')
                            ->maxLength(255),
                        TextInput::make('capacity')
                            ->label('الطاقة')
                            ->numeric()
                            ->default(0),
                        Textarea::make('notes')
                            ->label('ملاحظات')
                            ->rows(3),
                    ]),
            ]);
    }
}

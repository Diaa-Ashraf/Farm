<?php

namespace App\Filament\Resources\FeedTypes\Schemas;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class FeedTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم نوع العلف')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('default_price')
                            ->label('السعر الافتراضي')
                            ->numeric(),
                    ]),
            ]);
    }
}

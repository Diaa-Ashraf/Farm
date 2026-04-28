<?php

namespace App\Filament\Resources\MedicineInvoices\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('medicine_name')
                            ->label('اسم الدواء')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('unit')
                            ->label('الوحدة')
                            ->required()
                            ->default('علبة')
                            ->maxLength(255),
                        TextInput::make('quantity')
                            ->label('الكمية')
                            ->numeric()
                            ->required(),
                        TextInput::make('unit_price')
                            ->label('سعر الوحدة')
                            ->numeric()
                            ->required(),
                        TextInput::make('total')
                            ->label('الإجمالي')
                            ->numeric()
                            ->disabled()
                            ->dehydrateStateUsing(fn($state, $get) => $get('quantity') * $get('unit_price'))
                            ->default(0),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('medicine_name')
            ->columns([
                TextColumn::make('medicine_name')
                    ->label('اسم الدواء')
                    ->searchable(),
                TextColumn::make('unit')
                    ->label('الوحدة'),
                TextColumn::make('quantity')
                    ->label('الكمية'),
                TextColumn::make('unit_price')
                    ->label('سعر الوحدة'),
                TextColumn::make('total')
                    ->label('الإجمالي'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

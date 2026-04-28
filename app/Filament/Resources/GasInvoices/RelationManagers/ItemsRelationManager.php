<?php

namespace App\Filament\Resources\GasInvoices\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
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
                        Select::make('gas_type_id')
                            ->label('نوع الأنبوب')
                            ->relationship('gasType', 'name')
                            ->preload()
                            ->required(),
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
            ->recordTitleAttribute('gas_type_id')
            ->columns([
                TextColumn::make('gasType.name')
                    ->label('نوع الأنبوب')
                    ->searchable(),
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

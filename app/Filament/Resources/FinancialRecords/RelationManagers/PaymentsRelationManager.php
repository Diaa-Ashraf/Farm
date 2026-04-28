<?php

namespace App\Filament\Resources\FinancialRecords\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('amount')
                            ->label('المبلغ')
                            ->numeric()
                            ->required(),
                        DatePicker::make('payment_date')
                            ->label('تاريخ الدفعة')
                            ->required(),
                        TextInput::make('payment_method')
                            ->label('طريقة الدفع')
                            ->maxLength(255),
                        TextInput::make('notes')
                            ->label('ملاحظات')
                            ->maxLength(255),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('amount')
            ->columns([
                TextColumn::make('amount')
                    ->label('المبلغ'),
                TextColumn::make('payment_date')
                    ->label('التاريخ')
                    ->date(),
                TextColumn::make('payment_method')
                    ->label('طريقة الدفع'),
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

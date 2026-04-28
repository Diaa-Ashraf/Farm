<?php

namespace App\Filament\Resources\Workers\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkerCyclesRelationManager extends RelationManager
{
    protected static string $relationship = 'workerCycles';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('cycle_id')
                            ->label('الدورة')
                            ->relationship('cycle', 'name')
                            ->preload()
                            ->required(),
                        DatePicker::make('start_date')
                            ->label('تاريخ البداية')
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('تاريخ النهاية'),
                        TextInput::make('days_worked')
                            ->label('أيام العمل')
                            ->numeric(),
                        TextInput::make('total_wage')
                            ->label('الأجر الإجمالي')
                            ->numeric(),
                        TextInput::make('advances_total')
                            ->label('إجمالي السلف')
                            ->numeric()
                            ->default(0),
                        TextInput::make('net_wage')
                            ->label('صافي الأجر')
                            ->numeric(),
                        Textarea::make('notes')
                            ->label('ملاحظات')
                            ->rows(3),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('cycle.name')
            ->columns([
                TextColumn::make('cycle.name')
                    ->label('الدورة'),
                TextColumn::make('start_date')
                    ->label('تاريخ البداية')
                    ->date(),
                TextColumn::make('end_date')
                    ->label('تاريخ النهاية')
                    ->date(),
                TextColumn::make('days_worked')
                    ->label('أيام العمل'),
                TextColumn::make('total_wage')
                    ->label('الأجر الإجمالي'),
                TextColumn::make('net_wage')
                    ->label('صافي الأجر'),
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

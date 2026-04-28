<?php

namespace App\Filament\Resources\FinancialRecords;

use App\Filament\Resources\FinancialRecords\Pages\CreateFinancialRecord;
use App\Filament\Resources\FinancialRecords\Pages\EditFinancialRecord;
use App\Filament\Resources\FinancialRecords\Pages\ListFinancialRecords;
use App\Filament\Resources\FinancialRecords\Schemas\FinancialRecordForm;
use App\Filament\Resources\FinancialRecords\RelationManagers\PaymentsRelationManager;
use App\Filament\Resources\FinancialRecords\Tables\FinancialRecordsTable;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use App\Models\FinancialRecord;


class FinancialRecordResource extends Resource
{
    protected static ?string $model = FinancialRecord::class;
    protected static ?string $modelLabel = 'سجل مالي';
    protected static ?string $pluralModelLabel = 'السجلات المالية';
    protected static ?string $navigationLabel = 'السجلات المالية';
    protected static string|UnitEnum|null   $navigationGroup = 'التقارير';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $recordTitleAttribute = 'party_name';

    public static function form(Schema $schema): Schema
    {
        return FinancialRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FinancialRecordsTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['cycle', 'payments']);
    }

    public static function getRelations(): array
    {
        return [
            PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFinancialRecords::route('/'),
            'create' => CreateFinancialRecord::route('/create'),
            'edit' => EditFinancialRecord::route('/{record}/edit'),
        ];
    }
}




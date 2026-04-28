<?php

namespace App\Filament\Resources\GasTypes;

use App\Filament\Resources\GasTypes\Pages\CreateGasType;
use App\Filament\Resources\GasTypes\Pages\EditGasType;
use App\Filament\Resources\GasTypes\Pages\ListGasTypes;
use App\Filament\Resources\GasTypes\RelationManagers\GasInvoiceItemsRelationManager;
use App\Filament\Resources\GasTypes\Schemas\GasTypeForm;
use App\Filament\Resources\GasTypes\Tables\GasTypesTable;
use App\Models\GasType;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class GasTypeResource extends Resource
{
    protected static ?string $model = GasType::class;
    protected static ?string $modelLabel = 'نوع وقود';
    protected static ?string $pluralModelLabel = 'أنواع الوقود';
    protected static ?string $navigationLabel = 'أنواع الوقود';
    protected static string|UnitEnum|null   $navigationGroup = 'الإعدادات';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return GasTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GasTypesTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['gasInvoiceItems']);
    }

    public static function getRelations(): array
    {
        return [
            GasInvoiceItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGasTypes::route('/'),
            'create' => CreateGasType::route('/create'),
            'edit' => EditGasType::route('/{record}/edit'),
        ];
    }
}




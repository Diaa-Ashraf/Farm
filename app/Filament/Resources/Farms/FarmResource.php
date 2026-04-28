<?php

namespace App\Filament\Resources\Farms;

use App\Filament\Resources\Farms\Pages\CreateFarm;
use App\Filament\Resources\Farms\Pages\EditFarm;
use App\Filament\Resources\Farms\Pages\ListFarms;
use App\Filament\Resources\Farms\Schemas\FarmForm;
use App\Filament\Resources\Farms\Tables\FarmsTable;
use App\Models\Farm;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class FarmResource extends Resource
{
    protected static ?string $model = Farm::class;
    protected static ?string $modelLabel = 'مزرعة';
    protected static ?string $pluralModelLabel = 'المزارع';
    protected static ?string $navigationLabel = 'المزارع';
        protected static string|UnitEnum|null   $navigationGroup = 'إدارة المزرعة';
        protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return FarmForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FarmsTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['cycles', 'workers']);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFarms::route('/'),
            'create' => CreateFarm::route('/create'),
            'edit' => EditFarm::route('/{record}/edit'),
        ];
    }
}




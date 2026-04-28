<?php

namespace App\Filament\Resources\Buyers;

use App\Filament\Resources\Buyers\Pages\CreateBuyer;
use App\Filament\Resources\Buyers\Pages\EditBuyer;
use App\Filament\Resources\Buyers\Pages\ListBuyers;
use App\Filament\Resources\Buyers\RelationManagers\SalesRelationManager;
use App\Filament\Resources\Buyers\Schemas\BuyerForm;
use App\Filament\Resources\Buyers\Tables\BuyersTable;
use App\Models\Buyer;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;


class BuyerResource extends Resource
{
    protected static ?string $model = Buyer::class;
    protected static ?string $modelLabel = 'مشتري';
    protected static ?string $pluralModelLabel = 'المشترين';
    protected static ?string $navigationLabel = 'المشترين';
    protected static string|UnitEnum|null   $navigationGroup = 'الإعدادات';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return BuyerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BuyersTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['sales']);
    }

    public static function getRelations(): array
    {
        return [
            SalesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBuyers::route('/'),
            'create' => CreateBuyer::route('/create'),
            'edit' => EditBuyer::route('/{record}/edit'),
        ];
    }
}




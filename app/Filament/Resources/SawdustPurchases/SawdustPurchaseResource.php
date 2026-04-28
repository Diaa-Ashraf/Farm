<?php

namespace App\Filament\Resources\SawdustPurchases;

use App\Filament\Resources\SawdustPurchases\Pages\CreateSawdustPurchase;
use App\Filament\Resources\SawdustPurchases\Pages\EditSawdustPurchase;
use App\Filament\Resources\SawdustPurchases\Pages\ListSawdustPurchases;
use App\Filament\Resources\SawdustPurchases\Schemas\SawdustPurchaseForm;
use App\Filament\Resources\SawdustPurchases\Tables\SawdustPurchasesTable;
use App\Models\SawdustPurchase;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SawdustPurchaseResource extends Resource
{
    protected static ?string $model = SawdustPurchase::class;
    protected static ?string $modelLabel = 'شراء نشارة';
    protected static ?string $pluralModelLabel = 'مشتريات النشارة';
    protected static ?string $navigationLabel = 'مشتريات النشارة';
    protected static string|UnitEnum|null   $navigationGroup = 'المتابعة اليومية';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';


    protected static ?string $recordTitleAttribute = 'supplier_name';

    public static function form(Schema $schema): Schema
    {
        return SawdustPurchaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SawdustPurchasesTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['cycle']);
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
            'index' => ListSawdustPurchases::route('/'),
            'create' => CreateSawdustPurchase::route('/create'),
            'edit' => EditSawdustPurchase::route('/{record}/edit'),
        ];
    }
}




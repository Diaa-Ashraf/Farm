<?php

namespace App\Filament\Resources\FeedPurchases;

use App\Filament\Resources\FeedPurchases\Pages\CreateFeedPurchase;
use App\Filament\Resources\FeedPurchases\Pages\EditFeedPurchase;
use App\Filament\Resources\FeedPurchases\Pages\ListFeedPurchases;
use App\Filament\Resources\FeedPurchases\Schemas\FeedPurchaseForm;
use App\Filament\Resources\FeedPurchases\Tables\FeedPurchasesTable;
use App\Models\FeedPurchase;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class FeedPurchaseResource extends Resource
{
    protected static ?string $model = FeedPurchase::class;
    protected static ?string $modelLabel = 'شراء علف';
    protected static ?string $pluralModelLabel = 'مشتريات العلف';
    protected static ?string $navigationLabel = 'مشتريات العلف';
   protected static string|UnitEnum|null   $navigationGroup = 'المصروفات';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-truck';


    protected static ?string $recordTitleAttribute = 'unit';

    public static function form(Schema $schema): Schema
    {
        return FeedPurchaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedPurchasesTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['cycle', 'feedType']);
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
            'index' => ListFeedPurchases::route('/'),
            'create' => CreateFeedPurchase::route('/create'),
            'edit' => EditFeedPurchase::route('/{record}/edit'),
        ];
    }
}




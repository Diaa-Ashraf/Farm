<?php

namespace App\Filament\Resources\FeedTypes;

use App\Filament\Resources\FeedTypes\Pages\CreateFeedType;
use App\Filament\Resources\FeedTypes\Pages\EditFeedType;
use App\Filament\Resources\FeedTypes\Pages\ListFeedTypes;
use App\Filament\Resources\FeedTypes\RelationManagers\FeedPurchasesRelationManager;
use App\Filament\Resources\FeedTypes\Schemas\FeedTypeForm;
use App\Filament\Resources\FeedTypes\Tables\FeedTypesTable;
use App\Models\FeedType;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class FeedTypeResource extends Resource
{
    protected static ?string $model = FeedType::class;
    protected static ?string $modelLabel = 'نوع علف';
    protected static ?string $pluralModelLabel = 'أنواع العلف';
    protected static ?string $navigationLabel = 'أنواع العلف';
  protected static string|UnitEnum|null   $navigationGroup ='الإعدادات';
  protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return FeedTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedTypesTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['feedPurchases']);
    }

    public static function getRelations(): array
    {
        return [
            FeedPurchasesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeedTypes::route('/'),
            'create' => CreateFeedType::route('/create'),
            'edit' => EditFeedType::route('/{record}/edit'),
        ];
    }
}




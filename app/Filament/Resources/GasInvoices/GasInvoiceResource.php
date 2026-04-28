<?php

namespace App\Filament\Resources\GasInvoices;

use App\Filament\Resources\GasInvoices\Pages\CreateGasInvoice;
use App\Filament\Resources\GasInvoices\Pages\EditGasInvoice;
use App\Filament\Resources\GasInvoices\Pages\ListGasInvoices;
use App\Filament\Resources\GasInvoices\Schemas\GasInvoiceForm;
use App\Filament\Resources\GasInvoices\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\GasInvoices\Tables\GasInvoicesTable;
use App\Models\GasInvoice;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class GasInvoiceResource extends Resource
{
    protected static ?string $model = GasInvoice::class;
    protected static ?string $modelLabel = 'فاتورة وقود';
    protected static ?string $pluralModelLabel = 'فواتير الوقود';
    protected static ?string $navigationLabel = 'فواتير الوقود';
    protected static string|UnitEnum|null $navigationGroup = 'المصروفات';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-fire';

    protected static ?string $recordTitleAttribute = 'supplier_name';

    public static function form(Schema $schema): Schema
    {
        return GasInvoiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GasInvoicesTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['cycle', 'items']);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGasInvoices::route('/'),
            'create' => CreateGasInvoice::route('/create'),
            'edit' => EditGasInvoice::route('/{record}/edit'),
        ];
    }
}




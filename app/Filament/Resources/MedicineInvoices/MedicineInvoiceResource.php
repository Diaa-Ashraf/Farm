<?php

namespace App\Filament\Resources\MedicineInvoices;

use App\Filament\Resources\MedicineInvoices\Pages\CreateMedicineInvoice;
use App\Filament\Resources\MedicineInvoices\Pages\EditMedicineInvoice;
use App\Filament\Resources\MedicineInvoices\Pages\ListMedicineInvoices;
use App\Filament\Resources\MedicineInvoices\Schemas\MedicineInvoiceForm;
use App\Filament\Resources\MedicineInvoices\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\MedicineInvoices\Tables\MedicineInvoicesTable;
use App\Models\MedicineInvoice;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class MedicineInvoiceResource extends Resource
{
    protected static ?string $model = MedicineInvoice::class;
    protected static ?string $modelLabel = 'فاتورة دواء';
    protected static ?string $pluralModelLabel = 'فواتير الأدوية';
    protected static ?string $navigationLabel = 'فواتير الأدوية';
    protected static string|UnitEnum|null $navigationGroup = 'المصروفات';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $recordTitleAttribute = 'pharmacy_name';

    public static function form(Schema $schema): Schema
    {
        return MedicineInvoiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MedicineInvoicesTable::configure($table);
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
            'index' => ListMedicineInvoices::route('/'),
            'create' => CreateMedicineInvoice::route('/create'),
            'edit' => EditMedicineInvoice::route('/{record}/edit'),
        ];
    }
}




<?php

namespace App\Filament\Resources\MiscellaneousExpenses;

use App\Filament\Resources\MiscellaneousExpenses\Pages\CreateMiscellaneousExpense;
use App\Filament\Resources\MiscellaneousExpenses\Pages\EditMiscellaneousExpense;
use App\Filament\Resources\MiscellaneousExpenses\Pages\ListMiscellaneousExpenses;
use App\Filament\Resources\MiscellaneousExpenses\Schemas\MiscellaneousExpenseForm;
use App\Filament\Resources\MiscellaneousExpenses\Tables\MiscellaneousExpensesTable;
use App\Models\MiscellaneousExpense;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class MiscellaneousExpenseResource extends Resource
{
    protected static ?string $model = MiscellaneousExpense::class;
    protected static ?string $modelLabel = 'مصروف متنوع';
    protected static ?string $pluralModelLabel = 'المصروفات المتنوعة';
    protected static ?string $navigationLabel = 'المصروفات النثرية';
    protected static string|UnitEnum|null   $navigationGroup = 'المصروفات';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    // protected static ?string $recordTitleAttribute = 'description';

    public static function form(Schema $schema): Schema
    {
        return MiscellaneousExpenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MiscellaneousExpensesTable::configure($table);
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
            'index' => ListMiscellaneousExpenses::route('/'),
            'create' => CreateMiscellaneousExpense::route('/create'),
            'edit' => EditMiscellaneousExpense::route('/{record}/edit'),
        ];
    }
}




<?php

namespace App\Filament\Resources\CycleClosings;

use App\Filament\Resources\CycleClosings\Pages\CreateCycleClosing;
use App\Filament\Resources\CycleClosings\Pages\EditCycleClosing;
use App\Filament\Resources\CycleClosings\Pages\ListCycleClosings;
use App\Filament\Resources\CycleClosings\Schemas\CycleClosingForm;
use App\Filament\Resources\CycleClosings\Tables\CycleClosingsTable;
use App\Models\CycleClosing;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CycleClosingResource extends Resource
{
    protected static ?string $model = CycleClosing::class;
    protected static ?string $modelLabel = 'إغلاق دورة';
    protected static ?string $pluralModelLabel = 'إغلاقات الدورات';
    protected static ?string $navigationLabel  = 'إغلاق الدورات';
    protected static string|UnitEnum|null $navigationGroup = 'إدارة المزرعة';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-lock-closed';


    protected static ?string $recordTitleAttribute = 'closing_date';

    public static function form(Schema $schema): Schema
    {
        return CycleClosingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CycleClosingsTable::configure($table);
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
            'index' => ListCycleClosings::route('/'),
            'create' => CreateCycleClosing::route('/create'),
            'edit' => EditCycleClosing::route('/{record}/edit'),
        ];
    }
}




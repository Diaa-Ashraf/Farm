<?php

namespace App\Filament\Resources\MortalityLogs;

use App\Filament\Resources\MortalityLogs\Pages\CreateMortalityLog;
use App\Filament\Resources\MortalityLogs\Pages\EditMortalityLog;
use App\Filament\Resources\MortalityLogs\Pages\ListMortalityLogs;
use App\Filament\Resources\MortalityLogs\Schemas\MortalityLogForm;
use App\Filament\Resources\MortalityLogs\Tables\MortalityLogsTable;
use App\Models\MortalityLog;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class MortalityLogResource extends Resource
{
    protected static ?string $model = MortalityLog::class;
    protected static ?string $modelLabel = 'سجل وفيات';
    protected static ?string $pluralModelLabel = 'سجلات الوفيات';
    protected static ?string $navigationLabel = 'النافِق';
    protected static string|UnitEnum|null   $navigationGroup = 'المتابعة اليومية';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-circle';

    protected static ?string $recordTitleAttribute = 'log_date';

    public static function form(Schema $schema): Schema
    {
        return MortalityLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MortalityLogsTable::configure($table);
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
            'index' => ListMortalityLogs::route('/'),
            'create' => CreateMortalityLog::route('/create'),
            'edit' => EditMortalityLog::route('/{record}/edit'),
        ];
    }
}




<?php

namespace App\Filament\Resources\WorkerCycles;

use App\Filament\Resources\WorkerCycles\Pages\CreateWorkerCycle;
use App\Filament\Resources\WorkerCycles\Pages\EditWorkerCycle;
use App\Filament\Resources\WorkerCycles\Pages\ListWorkerCycles;
use App\Filament\Resources\WorkerCycles\Schemas\WorkerCycleForm;
use App\Filament\Resources\WorkerCycles\RelationManagers\AdvancesRelationManager;
use App\Filament\Resources\WorkerCycles\Tables\WorkerCyclesTable;
use App\Models\WorkerCycle;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class WorkerCycleResource extends Resource
{
    protected static ?string $model = WorkerCycle::class;
    protected static ?string $modelLabel = 'دورة عامل';
    protected static ?string $pluralModelLabel = 'دورات العمال';
    protected static ?string $navigationLabel = 'دورات العمال';
    protected static string|UnitEnum|null   $navigationGroup = 'المصروفات';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $recordTitleAttribute = 'worker_id';

    public static function form(Schema $schema): Schema
    {
        return WorkerCycleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkerCyclesTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['cycle', 'worker', 'advances']);
    }

    public static function getRelations(): array
    {
        return [
            AdvancesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkerCycles::route('/'),
            'create' => CreateWorkerCycle::route('/create'),
            'edit' => EditWorkerCycle::route('/{record}/edit'),
        ];
    }
}




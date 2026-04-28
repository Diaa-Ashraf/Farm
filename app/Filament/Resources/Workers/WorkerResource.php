<?php

namespace App\Filament\Resources\Workers;

use App\Filament\Resources\Workers\Pages\CreateWorker;
use App\Filament\Resources\Workers\Pages\EditWorker;
use App\Filament\Resources\Workers\Pages\ListWorkers;
use App\Filament\Resources\Workers\RelationManagers\WorkerCyclesRelationManager;
use App\Filament\Resources\Workers\Schemas\WorkerForm;
use App\Filament\Resources\Workers\Tables\WorkersTable;
use App\Models\Worker;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class WorkerResource extends Resource
{
    protected static ?string $model = Worker::class;
    protected static ?string $modelLabel = 'عامل';
    protected static ?string $pluralModelLabel = 'العمال';
    protected static ?string $navigationLabel = 'العمال';
    protected static string|UnitEnum|null   $navigationGroup = 'الإعدادات';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return WorkerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkersTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['farm', 'workerCycles']);
    }

    public static function getRelations(): array
    {
        return [
            WorkerCyclesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkers::route('/'),
            'create' => CreateWorker::route('/create'),
            'edit' => EditWorker::route('/{record}/edit'),
        ];
    }
}




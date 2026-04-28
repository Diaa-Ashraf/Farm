<?php

namespace App\Filament\Resources\WorkerCycles\Pages;

use App\Filament\Resources\WorkerCycles\WorkerCycleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkerCycles extends ListRecords
{
    protected static string $resource = WorkerCycleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

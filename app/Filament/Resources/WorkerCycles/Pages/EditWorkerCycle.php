<?php

namespace App\Filament\Resources\WorkerCycles\Pages;

use App\Filament\Resources\WorkerCycles\WorkerCycleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkerCycle extends EditRecord
{
    protected static string $resource = WorkerCycleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

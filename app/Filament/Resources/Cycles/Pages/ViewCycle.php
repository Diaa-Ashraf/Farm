<?php

namespace App\Filament\Resources\Cycles\Pages;

use App\Filament\Resources\Cycles\CycleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCycle extends ViewRecord
{
    protected static string $resource = CycleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

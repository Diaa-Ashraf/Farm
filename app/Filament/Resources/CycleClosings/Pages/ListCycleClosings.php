<?php

namespace App\Filament\Resources\CycleClosings\Pages;

use App\Filament\Resources\CycleClosings\CycleClosingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCycleClosings extends ListRecords
{
    protected static string $resource = CycleClosingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

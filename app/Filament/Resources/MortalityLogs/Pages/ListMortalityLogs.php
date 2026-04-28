<?php

namespace App\Filament\Resources\MortalityLogs\Pages;

use App\Filament\Resources\MortalityLogs\MortalityLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMortalityLogs extends ListRecords
{
    protected static string $resource = MortalityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

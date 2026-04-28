<?php

namespace App\Filament\Resources\GasTypes\Pages;

use App\Filament\Resources\GasTypes\GasTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGasTypes extends ListRecords
{
    protected static string $resource = GasTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

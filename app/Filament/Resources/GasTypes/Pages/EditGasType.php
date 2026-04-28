<?php

namespace App\Filament\Resources\GasTypes\Pages;

use App\Filament\Resources\GasTypes\GasTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGasType extends EditRecord
{
    protected static string $resource = GasTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

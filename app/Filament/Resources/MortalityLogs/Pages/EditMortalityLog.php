<?php

namespace App\Filament\Resources\MortalityLogs\Pages;

use App\Filament\Resources\MortalityLogs\MortalityLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMortalityLog extends EditRecord
{
    protected static string $resource = MortalityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

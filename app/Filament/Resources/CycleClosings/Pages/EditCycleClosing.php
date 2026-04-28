<?php

namespace App\Filament\Resources\CycleClosings\Pages;

use App\Filament\Resources\CycleClosings\CycleClosingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCycleClosing extends EditRecord
{
    protected static string $resource = CycleClosingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

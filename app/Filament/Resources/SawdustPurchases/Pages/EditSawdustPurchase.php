<?php

namespace App\Filament\Resources\SawdustPurchases\Pages;

use App\Filament\Resources\SawdustPurchases\SawdustPurchaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSawdustPurchase extends EditRecord
{
    protected static string $resource = SawdustPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\SawdustPurchases\Pages;

use App\Filament\Resources\SawdustPurchases\SawdustPurchaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSawdustPurchases extends ListRecords
{
    protected static string $resource = SawdustPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

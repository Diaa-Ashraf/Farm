<?php

namespace App\Filament\Resources\GasInvoices\Pages;

use App\Filament\Resources\GasInvoices\GasInvoiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGasInvoices extends ListRecords
{
    protected static string $resource = GasInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

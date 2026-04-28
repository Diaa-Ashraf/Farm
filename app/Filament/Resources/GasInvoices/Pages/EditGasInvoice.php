<?php

namespace App\Filament\Resources\GasInvoices\Pages;

use App\Filament\Resources\GasInvoices\GasInvoiceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGasInvoice extends EditRecord
{
    protected static string $resource = GasInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

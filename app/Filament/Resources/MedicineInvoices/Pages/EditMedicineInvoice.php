<?php

namespace App\Filament\Resources\MedicineInvoices\Pages;

use App\Filament\Resources\MedicineInvoices\MedicineInvoiceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMedicineInvoice extends EditRecord
{
    protected static string $resource = MedicineInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

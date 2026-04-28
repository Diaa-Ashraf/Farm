<?php

namespace App\Filament\Resources\MedicineInvoices\Pages;

use App\Filament\Resources\MedicineInvoices\MedicineInvoiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMedicineInvoices extends ListRecords
{
    protected static string $resource = MedicineInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

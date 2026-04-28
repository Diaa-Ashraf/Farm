<?php

namespace App\Filament\Resources\MiscellaneousExpenses\Pages;

use App\Filament\Resources\MiscellaneousExpenses\MiscellaneousExpenseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMiscellaneousExpense extends CreateRecord
{
    protected static string $resource = MiscellaneousExpenseResource::class;

    public function getTitle(): string
    {
        return 'إضافة مصروف';
    }
}

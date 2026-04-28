<?php

namespace App\Filament\Resources\FeedPurchases\Pages;

use App\Filament\Resources\FeedPurchases\FeedPurchaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedPurchase extends EditRecord
{
    protected static string $resource = FeedPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

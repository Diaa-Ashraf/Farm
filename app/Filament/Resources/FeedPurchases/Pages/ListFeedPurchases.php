<?php

namespace App\Filament\Resources\FeedPurchases\Pages;

use App\Filament\Resources\FeedPurchases\FeedPurchaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeedPurchases extends ListRecords
{
    protected static string $resource = FeedPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

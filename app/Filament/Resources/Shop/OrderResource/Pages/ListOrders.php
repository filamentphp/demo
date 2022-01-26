<?php

namespace App\Filament\Resources\Shop\OrderResource\Pages;

use App\Filament\Resources\Shop\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getDeleteTableBulkAction(): Tables\Actions\BulkAction
    {
        return parent::getDeleteTableBulkAction()
            ->action(fn () => $this->notify(
                'warning',
                'Now, now, don’t be cheeky, leave some records for others to play with!',
            ));
    }
}

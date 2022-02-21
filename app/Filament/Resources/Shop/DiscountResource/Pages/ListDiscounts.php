<?php

namespace App\Filament\Resources\Shop\DiscountResource\Pages;

use App\Filament\Resources\Shop\DiscountResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListDiscounts extends ListRecords
{
    protected static string $resource = DiscountResource::class;

    protected function getDeleteBulkAction(): Tables\Actions\BulkAction
    {
        return parent::getDeleteBulkAction()
            ->action(fn () => $this->notify(
                'warning',
                'Now, now, don’t be cheeky, leave some records for others to play with!',
            ));
    }
}

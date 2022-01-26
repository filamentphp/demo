<?php

namespace App\Filament\Resources\Shop\ProductResource\Pages;

use App\Filament\Resources\Shop\ProductResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getDeleteTableBulkAction(): Tables\Actions\BulkAction
    {
        return parent::getDeleteTableBulkAction()
            ->action(fn () => $this->notify(
                'warning',
                'Now, now, don’t be cheeky, leave some records for others to play with!',
            ));
    }
}

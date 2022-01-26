<?php

namespace App\Filament\Resources\Shop\BrandResource\Pages;

use App\Filament\Resources\Shop\BrandResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListBrands extends ListRecords
{
    protected static string $resource = BrandResource::class;

    protected function getDeleteTableBulkAction(): Tables\Actions\BulkAction
    {
        return parent::getDeleteTableBulkAction()
            ->action(fn () => $this->notify(
                'warning',
                'Now, now, don’t be cheeky, leave some records for others to play with!',
            ));
    }
}

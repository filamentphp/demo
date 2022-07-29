<?php

namespace App\Filament\Resources\Shop\BrandResource\Pages;

use App\Filament\Resources\Shop\BrandResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListBrands extends ListRecords
{
    protected static string $resource = BrandResource::class;

    protected function getDeleteBulkAction(): Tables\Actions\BulkAction
    {
        return parent::getDeleteBulkAction()
            ->action(fn () => $this->notify(
                'warning',
                'Now, now, don’t be cheeky, leave some records for others to play with!',
            ));
    }

    protected function getTableReorderColumn(): ?string
    {
        return 'sort';
    }
}

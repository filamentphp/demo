<?php

namespace App\Filament\Resources\Shop\CategoryResource\Pages;

use App\Filament\Resources\Shop\CategoryResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getDeleteBulkAction(): Tables\Actions\BulkAction
    {
        return parent::getDeleteBulkAction()
            ->action(fn () => $this->notify(
                'warning',
                'Now, now, don’t be cheeky, leave some records for others to play with!',
            ));
    }
}

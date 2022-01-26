<?php

namespace App\Filament\Resources\Blog\CategoryResource\Pages;

use App\Filament\Resources\Blog\CategoryResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getDeleteTableBulkAction(): Tables\Actions\BulkAction
    {
        return parent::getDeleteTableBulkAction()
            ->action(fn () => $this->notify(
                'warning',
                'Now, now, don’t be cheeky, leave some records for others to play with!',
            ));
    }
}

<?php

namespace App\Filament\Resources\Blog\CategoryResource\Pages;

use App\Filament\Resources\Blog\CategoryResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListCategories extends ListRecords
{
    use ListRecords\Concerns\CanCreateRecords;
    use ListRecords\Concerns\CanEditRecords;

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

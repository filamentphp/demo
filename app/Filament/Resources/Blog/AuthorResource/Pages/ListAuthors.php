<?php

namespace App\Filament\Resources\Blog\AuthorResource\Pages;

use App\Filament\Resources\Blog\AuthorResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListAuthors extends ListRecords
{
    use ListRecords\Concerns\CanCreateRecords;
    use ListRecords\Concerns\CanEditRecords;

    protected static string $resource = AuthorResource::class;

    protected function getDeleteBulkAction(): Tables\Actions\BulkAction
    {
        return parent::getDeleteBulkAction()
            ->action(fn () => $this->notify(
                'warning',
                'Now, now, don’t be cheeky, leave some records for others to play with!',
            ));
    }
}

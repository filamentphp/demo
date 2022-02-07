<?php

namespace App\Filament\Resources\Blog\PostResource\Pages;

use App\Filament\Resources\Blog\PostResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getDeleteBulkAction(): Tables\Actions\BulkAction
    {
        return parent::getDeleteBulkAction()
            ->action(fn () => $this->notify(
                'warning',
                'Now, now, don’t be cheeky, leave some records for others to play with!',
            ));
    }
}

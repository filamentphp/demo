<?php

namespace App\Filament\Resources\Shop\ReviewResource\Pages;

use App\Filament\Resources\Shop\ReviewResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListReviews extends ListRecords
{
    protected static string $resource = ReviewResource::class;

    protected function getDeleteTableBulkAction(): Tables\Actions\BulkAction
    {
        return parent::getDeleteTableBulkAction()
            ->action(fn () => $this->notify(
                'warning',
                'Now, now, don’t be cheeky, leave some records for others to play with!',
            ));
    }
}

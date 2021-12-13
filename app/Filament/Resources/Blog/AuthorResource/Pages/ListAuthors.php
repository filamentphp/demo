<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blog\AuthorResource\Pages;

use App\Filament\Resources\Blog\AuthorResource;
use Closure;
use Filament\Resources\Pages\ListRecords;

final class ListAuthors extends ListRecords
{
    protected static string $resource = AuthorResource::class;

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return null;
    }
}

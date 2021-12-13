<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blog\PostResource\Pages;

use App\Filament\Resources\Blog\PostResource;
use Closure;
use Filament\Resources\Pages\ListRecords;

final class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return null;
    }
}

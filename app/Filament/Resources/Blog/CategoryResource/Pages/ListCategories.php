<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blog\CategoryResource\Pages;

use App\Filament\Resources\Blog\CategoryResource;
use Closure;
use Filament\Resources\Pages\ListRecords;

final class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return null;
    }
}

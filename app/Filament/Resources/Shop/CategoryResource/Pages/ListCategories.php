<?php

namespace App\Filament\Resources\Shop\CategoryResource\Pages;

use App\Filament\Resources\Shop\CategoryResource;
use Closure;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;
}

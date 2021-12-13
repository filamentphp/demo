<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blog\CategoryResource\Pages;

use App\Filament\Resources\Blog\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}

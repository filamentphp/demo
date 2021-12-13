<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blog\CategoryResource\Pages;

use App\Filament\Resources\Blog\CategoryResource;
use Filament\Resources\Pages\EditRecord;

final class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;
}

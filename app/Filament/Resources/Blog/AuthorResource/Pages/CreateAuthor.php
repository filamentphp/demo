<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blog\AuthorResource\Pages;

use App\Filament\Resources\Blog\AuthorResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateAuthor extends CreateRecord
{
    protected static string $resource = AuthorResource::class;
}

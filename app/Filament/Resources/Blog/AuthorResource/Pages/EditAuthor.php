<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blog\AuthorResource\Pages;

use App\Filament\Resources\Blog\AuthorResource;
use Filament\Resources\Pages\EditRecord;

final class EditAuthor extends EditRecord
{
    protected static string $resource = AuthorResource::class;
}

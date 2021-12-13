<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blog\PostResource\Pages;

use App\Filament\Resources\Blog\PostResource;
use Filament\Resources\Pages\CreateRecord;

final class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}

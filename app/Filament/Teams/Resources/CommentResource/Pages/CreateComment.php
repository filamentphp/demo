<?php

namespace App\Filament\Teams\Resources\CommentResource\Pages;

use App\Filament\Teams\Resources\CommentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateComment extends CreateRecord
{
    protected static string $resource = CommentResource::class;
}

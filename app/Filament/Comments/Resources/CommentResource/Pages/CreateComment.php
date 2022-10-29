<?php

namespace App\Filament\Comments\Resources\CommentResource\Pages;

use App\Filament\Comments\Resources\CommentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateComment extends CreateRecord
{
    protected static string $resource = CommentResource::class;
}

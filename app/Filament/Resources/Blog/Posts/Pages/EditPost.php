<?php

namespace App\Filament\Resources\Blog\Posts\Pages;

use App\Filament\Resources\Blog\Posts\PostResource;
use App\Models\Blog\Post;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    public function getTitle(): string | Htmlable
    {
        /** @var Post */
        $record = $this->getRecord();

        return $record->title;
    }

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

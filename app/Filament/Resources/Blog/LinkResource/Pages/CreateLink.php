<?php

namespace App\Filament\Resources\Blog\LinkResource\Pages;

use App\Filament\Resources\Blog\LinkResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class CreateLink extends CreateRecord
{
    use Translatable;

    protected static string $resource = LinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}

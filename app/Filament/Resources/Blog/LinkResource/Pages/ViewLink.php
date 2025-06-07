<?php

namespace App\Filament\Resources\Blog\LinkResource\Pages;

use App\Filament\Resources\Blog\LinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ViewLink extends ViewRecord
{
    use Translatable;

    protected static string $resource = LinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}

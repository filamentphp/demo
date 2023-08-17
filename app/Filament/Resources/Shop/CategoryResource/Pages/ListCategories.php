<?php

namespace App\Filament\Resources\Shop\CategoryResource\Pages;

use App\Filament\Resources\Shop\CategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('impersonate')
                ->action('impersonate')
                ->label('Exporter tous en PDF')
                ->url(route('export.categories.all'), shouldOpenInNewTab: false)
                ->color('danger')
                ->icon('heroicon-o-document'),
            Actions\Action::make('impersonate')
                ->action('impersonate')
                ->label('Exporter par catégorie en PDF')
                ->url(route('export.categories.all'), shouldOpenInNewTab: false)
                ->color('danger')
                ->icon('heroicon-o-document'),
            Actions\CreateAction::make()
                ->label('Ajouter')
                ->icon('heroicon-o-plus'),
        ];
    }

        public function impersonate(): void
        {
            // ...
        }
}

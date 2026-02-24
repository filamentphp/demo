<?php

namespace App\Filament\Resources\Shop\Categories\Pages;

use App\Filament\Imports\Shop\CategoryImporter;
use App\Filament\Resources\Shop\Categories\CategoryResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getActions(): array
    {
        return [
            ImportAction::make()
                ->importer(CategoryImporter::class),
            CreateAction::make(),
        ];
    }
}

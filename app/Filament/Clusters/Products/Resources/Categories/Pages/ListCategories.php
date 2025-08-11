<?php

namespace App\Filament\Clusters\Products\Resources\Categories\Pages;

use App\Filament\Clusters\Products\Resources\Categories\CategoryResource;
use App\Filament\Imports\Shop\CategoryImporter;
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

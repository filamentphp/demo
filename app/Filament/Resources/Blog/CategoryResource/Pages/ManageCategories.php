<?php

namespace App\Filament\Resources\Blog\CategoryResource\Pages;

use App\Filament\Imports\Blog\CategoryImporter;
use App\Filament\Resources\Blog\CategoryResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCategories extends ManageRecords
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

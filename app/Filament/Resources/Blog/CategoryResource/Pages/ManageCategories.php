<?php

namespace App\Filament\Resources\Blog\CategoryResource\Pages;

use App\Filament\Resources\Blog\CategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Tables;

class ManageCategories extends ManageRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

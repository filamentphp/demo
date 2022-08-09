<?php

namespace App\Filament\Resources\Blog\AuthorResource\Pages;

use App\Filament\Resources\Blog\AuthorResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Tables;

class ManageAuthors extends ManageRecords
{
    protected static string $resource = AuthorResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

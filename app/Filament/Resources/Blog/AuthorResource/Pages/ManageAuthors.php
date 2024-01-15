<?php

namespace App\Filament\Resources\Blog\AuthorResource\Pages;

use App\Filament\Exports\Blog\AuthorExporter;
use App\Filament\Resources\Blog\AuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAuthors extends ManageRecords
{
    protected static string $resource = AuthorResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->exporter(AuthorExporter::class),
            Actions\CreateAction::make(),
        ];
    }
}

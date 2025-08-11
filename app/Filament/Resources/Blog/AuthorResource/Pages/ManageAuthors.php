<?php

namespace App\Filament\Resources\Blog\AuthorResource\Pages;

use App\Filament\Exports\Blog\AuthorExporter;
use App\Filament\Resources\Blog\AuthorResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAuthors extends ManageRecords
{
    protected static string $resource = AuthorResource::class;

    protected function getActions(): array
    {
        return [
            ExportAction::make()
                ->exporter(AuthorExporter::class),
            CreateAction::make(),
        ];
    }
}

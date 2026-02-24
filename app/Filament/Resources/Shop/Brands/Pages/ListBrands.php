<?php

namespace App\Filament\Resources\Shop\Brands\Pages;

use App\Filament\Exports\Shop\BrandExporter;
use App\Filament\Resources\Shop\Brands\BrandResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListBrands extends ListRecords
{
    protected static string $resource = BrandResource::class;

    protected function getActions(): array
    {
        return [
            ExportAction::make()
                ->exporter(BrandExporter::class),
            CreateAction::make(),
        ];
    }
}

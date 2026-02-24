<?php

namespace App\Filament\Resources\HR\Projects\Pages;

use App\Filament\Resources\HR\Projects\ProjectResource;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListProjects extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ProjectResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return ProjectResource::getWidgets();
    }
}

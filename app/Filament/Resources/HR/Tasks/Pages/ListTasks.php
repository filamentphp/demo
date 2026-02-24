<?php

namespace App\Filament\Resources\HR\Tasks\Pages;

use App\Filament\Resources\HR\Tasks\TaskResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

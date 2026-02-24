<?php

namespace App\Filament\Resources\HR\Departments\Pages;

use App\Filament\Resources\HR\Departments\DepartmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDepartments extends ManageRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\HR\Employees\Pages;

use App\Filament\Resources\HR\Employees\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}

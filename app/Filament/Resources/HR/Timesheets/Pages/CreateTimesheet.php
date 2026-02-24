<?php

namespace App\Filament\Resources\HR\Timesheets\Pages;

use App\Filament\Resources\HR\Timesheets\TimesheetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTimesheet extends CreateRecord
{
    protected static string $resource = TimesheetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['total_cost'] = (float) ($data['hours'] ?? 0) * (float) ($data['hourly_rate'] ?? 0);

        return $data;
    }
}

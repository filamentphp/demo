<?php

namespace App\Filament\Resources\HR\Timesheets\Pages;

use App\Filament\Resources\HR\Timesheets\TimesheetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTimesheet extends EditRecord
{
    protected static string $resource = TimesheetResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['total_cost'] = (float) ($data['hours'] ?? 0) * (float) ($data['hourly_rate'] ?? 0);

        return $data;
    }
}

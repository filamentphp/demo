<?php

namespace App\Filament\Resources\HR\Timesheets\Pages;

use App\Filament\Resources\HR\Timesheets\TimesheetResource;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListTimesheets extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = TimesheetResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return TimesheetResource::getWidgets();
    }
}

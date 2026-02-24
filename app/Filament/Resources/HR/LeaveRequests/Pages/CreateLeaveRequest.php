<?php

namespace App\Filament\Resources\HR\LeaveRequests\Pages;

use App\Filament\Resources\HR\LeaveRequests\LeaveRequestResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! empty($data['start_date']) && ! empty($data['end_date'])) {
            $days = Carbon::parse($data['start_date'])->diffInDays(Carbon::parse($data['end_date'])) + 1;
            $data['days_requested'] = max(0.5, $days);
        }

        return $data;
    }
}

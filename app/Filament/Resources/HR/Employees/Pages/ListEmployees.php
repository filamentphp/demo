<?php

namespace App\Filament\Resources\HR\Employees\Pages;

use App\Enums\LeaveStatus;
use App\Filament\Resources\HR\Employees\EmployeeResource;
use App\Filament\Resources\HR\LeaveRequests\LeaveRequestResource;
use App\Models\HR\LeaveRequest;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

class ListEmployees extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = EmployeeResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('leave_requests')
                ->label('Leave Requests')
                ->color('gray')
                ->icon(Heroicon::Calendar)
                ->badge(LeaveRequest::query()->where('status', LeaveStatus::Pending)->count())
                ->badgeColor('warning')
                ->url(LeaveRequestResource::getUrl('index')),
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return EmployeeResource::getWidgets();
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'active' => Tab::make('Active')
                ->query(fn ($query) => $query->where('is_active', true)),
            'inactive' => Tab::make('Inactive')
                ->query(fn ($query) => $query->where('is_active', false)),
        ];
    }
}

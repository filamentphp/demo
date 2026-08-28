<?php

namespace App\Filament\Resources\HR\Employees\Pages;

use App\Enums\LeaveStatus;
use App\Filament\Resources\HR\Employees\EmployeeResource;
use App\Filament\Resources\HR\LeaveRequests\LeaveRequestResource;
use App\Models\HR\Employee;
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
                ->label('Leave requests')
                ->color('gray')
                ->icon(Heroicon::Calendar)
                ->badge((string) LeaveRequest::query()->where('status', LeaveStatus::Pending)->count())
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
            null => Tab::make('All')
                ->badge(static fn (): int => Employee::query()->count())
                ->deferBadge(),
            'active' => Tab::make('Active')
                ->badge(static fn (): int => Employee::query()->where('is_active', true)->count())
                ->badgeColor('success')
                ->deferBadge()
                ->query(fn ($query) => $query->where('is_active', true)),
            'inactive' => Tab::make('Inactive')
                ->badge(static fn (): int => Employee::query()->where('is_active', false)->count())
                ->badgeColor('gray')
                ->deferBadge()
                ->query(fn ($query) => $query->where('is_active', false)),
        ];
    }
}

<?php

namespace App\Filament\Widgets;

use App\Enums\LeaveStatus;
use App\Models\HR\LeaveRequest;
use Filament\Widgets\ChartWidget;

class DepartmentLeaveLoadChart extends ChartWidget
{
    protected ?string $heading = 'Department Leave Load';

    protected static ?int $sort = 9;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $leaveRequests = LeaveRequest::query()
            ->whereIn('status', [LeaveStatus::Approved, LeaveStatus::Taken])
            ->whereYear('start_date', now()->year)
            ->with('employee.department')
            ->get();

        $byDepartment = $leaveRequests
            ->filter(fn (LeaveRequest $lr): bool => $lr->employee?->department !== null)
            ->groupBy(fn (LeaveRequest $lr): string => (string) $lr->employee?->department?->name)
            ->map(fn ($group) => round((float) $group->sum('days_requested'), 1))
            ->sortDesc();

        return [
            'datasets' => [
                [
                    'label' => 'Leave Days',
                    'data' => $byDepartment->values()->all(),
                    'backgroundColor' => '#8b5cf6',
                    'borderColor' => '#8b5cf6',
                ],
            ],
            'labels' => $byDepartment->keys()->all(),
        ];
    }
}

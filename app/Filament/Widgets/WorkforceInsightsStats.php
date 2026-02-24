<?php

namespace App\Filament\Widgets;

use App\Enums\EmploymentType;
use App\Models\HR\Department;
use App\Models\HR\Employee;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WorkforceInsightsStats extends BaseWidget
{
    protected static ?int $sort = 8;

    protected function getStats(): array
    {
        $activeEmployees = Employee::where('is_active', true)->get();
        $allEmployees = Employee::withTrashed()->get();
        $totalActive = $activeEmployees->count();

        $avgTenure = $activeEmployees
            ->avg(fn (Employee $e) => $e->hire_date->diffInMonths(now()) / 12);
        $avgTenure = $avgTenure !== null ? round($avgTenure, 1) : 0;

        $inactiveCount = $allEmployees->where('is_active', false)->count();
        $totalCount = $allEmployees->count();
        $turnoverRate = $totalCount > 0
            ? round(($inactiveCount / $totalCount) * 100, 1)
            : 0;

        $headcountLimit = (int) Department::where('is_active', true)->sum('headcount_limit');
        $openCapacity = max(0, $headcountLimit - $totalActive);

        $contractorCount = $activeEmployees->where('employment_type', EmploymentType::Contractor)->count();
        $contractorRatio = $totalActive > 0
            ? round(($contractorCount / $totalActive) * 100, 1)
            : 0;

        return [
            Stat::make('Avg Tenure', $avgTenure . ' yrs')
                ->description($totalActive . ' active employees')
                ->descriptionIcon(Heroicon::Clock)
                ->color('primary'),
            Stat::make('Turnover Rate', $turnoverRate . '%')
                ->description($inactiveCount . ' departed of ' . $totalCount)
                ->descriptionIcon(Heroicon::ArrowRightOnRectangle)
                ->color($turnoverRate > 20 ? 'danger' : 'warning'),
            Stat::make('Open Capacity', (string) $openCapacity)
                ->description('Limit: ' . $headcountLimit . ', filled: ' . $totalActive)
                ->descriptionIcon(Heroicon::UserPlus)
                ->color($openCapacity > 0 ? 'success' : 'gray'),
            Stat::make('Contractor Ratio', $contractorRatio . '%')
                ->description($contractorCount . ' contractors')
                ->descriptionIcon(Heroicon::Briefcase)
                ->color('info'),
        ];
    }
}

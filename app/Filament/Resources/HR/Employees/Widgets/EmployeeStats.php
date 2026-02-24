<?php

namespace App\Filament\Resources\HR\Employees\Widgets;

use App\Filament\Resources\HR\Employees\Pages\ListEmployees;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class EmployeeStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListEmployees::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $totalEmployees = $query->count();
        $activeEmployees = (clone $query)->where('is_active', true)->count();
        $avgSalary = (clone $query)->whereNotNull('salary')->avg('salary');
        $avgTenure = (clone $query)->whereNotNull('hire_date')
            ->pluck('hire_date')
            ->avg(fn (mixed $date): float => Carbon::parse($date)->diffInDays(now()) / 365.25);

        return [
            Stat::make('Total Employees', $totalEmployees),
            Stat::make('Active Employees', $activeEmployees)
                ->color('success'),
            Stat::make('Avg Salary', '$' . number_format((float) $avgSalary, 0))
                ->color('info'),
            Stat::make('Avg Tenure', number_format((float) $avgTenure, 1) . ' years')
                ->color('warning'),
        ];
    }
}

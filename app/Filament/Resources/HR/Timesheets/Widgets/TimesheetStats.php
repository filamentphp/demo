<?php

namespace App\Filament\Resources\HR\Timesheets\Widgets;

use App\Filament\Resources\HR\Timesheets\Pages\ListTimesheets;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TimesheetStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListTimesheets::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $hoursThisWeek = (clone $query)
            ->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('hours');

        $avgHoursPerDay = (clone $query)
            ->where('date', '>=', Carbon::now()->subDays(30))
            ->get(['date', 'hours'])
            ->groupBy('date')
            ->map(fn ($entries) => $entries->sum('hours'))
            ->avg();

        $totalEntries = (clone $query)->count();
        $billableEntries = (clone $query)->where('is_billable', true)->count();
        $billablePercent = $totalEntries > 0 ? round(($billableEntries / $totalEntries) * 100) : 0;

        $totalCost = (clone $query)->sum('total_cost');

        return [
            Stat::make('Hours This Week', number_format((float) $hoursThisWeek, 1))
                ->color('success'),
            Stat::make('Billable %', $billablePercent . '%')
                ->color('info'),
            Stat::make('Total Entries', number_format($totalEntries))
                ->color('warning'),
            Stat::make('Total Cost', '$' . number_format((float) $totalCost, 0))
                ->color('primary'),
        ];
    }
}

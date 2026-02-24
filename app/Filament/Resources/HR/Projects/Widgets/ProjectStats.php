<?php

namespace App\Filament\Resources\HR\Projects\Widgets;

use App\Enums\ProjectStatus;
use App\Filament\Resources\HR\Projects\Pages\ListProjects;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class ProjectStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListProjects::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $activeProjects = (clone $query)->where('status', ProjectStatus::Active)->count();
        $totalBudget = (clone $query)->sum('budget');
        $totalSpent = (clone $query)->sum('spent');

        $projects = (clone $query)
            ->where('estimated_hours', '>', 0)
            ->get(['actual_hours', 'estimated_hours']);

        $completionData = $projects->isEmpty() ? 0 : $projects
            ->map(fn (Model $project): float => ((float) $project->getAttribute('actual_hours') / (float) $project->getAttribute('estimated_hours')) * 100)
            ->avg();

        return [
            Stat::make('Active Projects', $activeProjects)
                ->color('success'),
            Stat::make('Total Budget', '$' . number_format((float) $totalBudget, 0))
                ->color('info'),
            Stat::make('Total Spent', '$' . number_format((float) $totalSpent, 0))
                ->color('warning'),
            Stat::make('Avg Completion', number_format((float) $completionData, 0) . '%')
                ->color('primary'),
        ];
    }
}

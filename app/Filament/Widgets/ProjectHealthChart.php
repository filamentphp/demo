<?php

namespace App\Filament\Widgets;

use App\Enums\ProjectStatus;
use App\Models\HR\Project;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ProjectHealthChart extends ChartWidget
{
    protected ?string $heading = 'Project Health';

    protected static ?int $sort = 10;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $projects = Project::where('status', ProjectStatus::Active)
            ->orderBy('name')
            ->limit(10)
            ->get();

        $budgetUsed = [];
        $timelineElapsed = [];

        foreach ($projects as $project) {
            $budgetPct = (float) $project->budget > 0
                ? min(200, round(((float) $project->spent / (float) $project->budget) * 100, 1))
                : 0;
            $budgetUsed[] = $budgetPct;

            if ($project->end_date !== null) {
                $totalDays = Carbon::parse($project->start_date)->diffInDays(Carbon::parse($project->end_date));
                $elapsedDays = Carbon::parse($project->start_date)->diffInDays(now());
                $timelinePct = $totalDays > 0
                    ? min(200, round(($elapsedDays / $totalDays) * 100, 1))
                    : 0;
            } else {
                $timelinePct = 0;
            }

            $timelineElapsed[] = $timelinePct;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Budget Used %',
                    'data' => $budgetUsed,
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Timeline Elapsed %',
                    'data' => $timelineElapsed,
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $projects->pluck('name')->map(fn (string $name) => strlen($name) > 20 ? substr($name, 0, 20) . '...' : $name)->all(),
        ];
    }
}

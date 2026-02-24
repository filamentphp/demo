<?php

namespace App\Filament\Widgets;

use App\Enums\EmploymentType;
use App\Models\HR\Employee;
use App\Models\HR\Timesheet;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class UtilizationRateChart extends ChartWidget
{
    protected ?string $heading = 'Utilization Rate';

    protected static ?int $sort = 11;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $fullTimeCount = Employee::where('is_active', true)
            ->where('employment_type', EmploymentType::FullTime)
            ->count();

        $weeklyCapacity = max(1, $fullTimeCount * 40);

        $weeks = collect(range(11, 0))->map(function (int $weeksAgo) use ($weeklyCapacity) {
            $start = Carbon::now()->subWeeks($weeksAgo)->startOfWeek();
            $end = Carbon::now()->subWeeks($weeksAgo)->endOfWeek();

            $billableHours = (float) Timesheet::whereBetween('date', [$start, $end])
                ->where('is_billable', true)
                ->sum('hours');

            return [
                'label' => $start->format('M d'),
                'utilization' => round(($billableHours / $weeklyCapacity) * 100, 1),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Utilization %',
                    'data' => $weeks->pluck('utilization')->all(),
                    'borderColor' => '#8b5cf6',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                    'fill' => 'start',
                ],
                [
                    'label' => 'Target (80%)',
                    'data' => array_fill(0, 12, 80),
                    'borderColor' => '#ef4444',
                    'borderDash' => [5, 5],
                    'pointRadius' => 0,
                    'fill' => false,
                ],
            ],
            'labels' => $weeks->pluck('label')->all(),
        ];
    }
}

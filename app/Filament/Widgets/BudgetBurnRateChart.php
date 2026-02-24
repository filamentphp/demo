<?php

namespace App\Filament\Widgets;

use App\Enums\ExpenseStatus;
use App\Enums\ProjectStatus;
use App\Models\HR\Expense;
use App\Models\HR\Project;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class BudgetBurnRateChart extends ChartWidget
{
    protected ?string $heading = 'Budget Burn Rate';

    protected static ?int $sort = 12;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $months = collect(range(11, 0))->map(fn (int $monthsAgo) => Carbon::now()->subMonths($monthsAgo)->startOfMonth());

        $approvedExpenses = Expense::whereIn('status', [ExpenseStatus::Approved, ExpenseStatus::Reimbursed])
            ->where('created_at', '>=', $months->first())
            ->get();

        $totalBudget = (float) Project::where('status', ProjectStatus::Active)->sum('budget');

        $cumulative = 0;
        $cumulativeData = [];
        $budgetLine = [];
        $labels = [];

        foreach ($months as $month) {
            $monthKey = $month->format('Y-m');
            $monthTotal = $approvedExpenses
                ->filter(fn (Expense $e) => $e->created_at?->format('Y-m') === $monthKey)
                ->sum('total_amount');

            $cumulative += (float) $monthTotal;
            $cumulativeData[] = round($cumulative, 2);
            $budgetLine[] = round($totalBudget, 2);
            $labels[] = $month->format('M Y');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cumulative Expenses',
                    'data' => $cumulativeData,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => 'start',
                ],
                [
                    'label' => 'Total Budget',
                    'data' => $budgetLine,
                    'borderColor' => '#22c55e',
                    'borderDash' => [5, 5],
                    'pointRadius' => 0,
                    'fill' => false,
                ],
            ],
            'labels' => $labels,
        ];
    }
}

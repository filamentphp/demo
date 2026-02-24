<?php

namespace App\Filament\Resources\HR\Expenses\Widgets;

use App\Enums\ExpenseStatus;
use App\Filament\Resources\HR\Expenses\Pages\ListExpenses;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class ExpenseStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListExpenses::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $pendingApproval = (clone $query)->where('status', ExpenseStatus::Submitted)->count();

        $approvedThisMonth = (clone $query)
            ->where('status', ExpenseStatus::Approved)
            ->where('approved_at', '>=', Carbon::now()->startOfMonth())
            ->count();

        $avgExpense = (clone $query)->avg('total_amount');

        $totalReimbursed = (clone $query)
            ->where('status', ExpenseStatus::Reimbursed)
            ->sum('total_amount');

        return [
            Stat::make('Pending Approval', $pendingApproval)
                ->color('warning'),
            Stat::make('Approved This Month', $approvedThisMonth)
                ->color('success'),
            Stat::make('Avg Expense', '$' . number_format((float) $avgExpense, 0))
                ->color('info'),
            Stat::make('Total Reimbursed', '$' . number_format((float) $totalReimbursed, 0))
                ->color('primary'),
        ];
    }
}

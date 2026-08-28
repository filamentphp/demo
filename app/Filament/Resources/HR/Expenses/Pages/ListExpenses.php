<?php

namespace App\Filament\Resources\HR\Expenses\Pages;

use App\Enums\ExpenseStatus;
use App\Filament\Resources\HR\Expenses\ExpenseResource;
use App\Models\HR\Expense;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListExpenses extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ExpenseResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return ExpenseResource::getWidgets();
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All')
                ->badge(static fn (): int => Expense::query()->count())
                ->deferBadge(),
            'draft' => Tab::make('Draft')
                ->badge(static fn (): int => Expense::query()->where('status', ExpenseStatus::Draft)->count())
                ->badgeColor(ExpenseStatus::Draft->getColor())
                ->deferBadge()
                ->query(fn ($query) => $query->where('status', ExpenseStatus::Draft)),
            'submitted' => Tab::make('Submitted')
                ->badge(static fn (): int => Expense::query()->where('status', ExpenseStatus::Submitted)->count())
                ->badgeColor(ExpenseStatus::Submitted->getColor())
                ->deferBadge()
                ->query(fn ($query) => $query->where('status', ExpenseStatus::Submitted)),
            'approved' => Tab::make('Approved')
                ->badge(static fn (): int => Expense::query()->where('status', ExpenseStatus::Approved)->count())
                ->badgeColor(ExpenseStatus::Approved->getColor())
                ->deferBadge()
                ->query(fn ($query) => $query->where('status', ExpenseStatus::Approved)),
            'rejected' => Tab::make('Rejected')
                ->badge(static fn (): int => Expense::query()->where('status', ExpenseStatus::Rejected)->count())
                ->badgeColor(ExpenseStatus::Rejected->getColor())
                ->deferBadge()
                ->query(fn ($query) => $query->where('status', ExpenseStatus::Rejected)),
            'reimbursed' => Tab::make('Reimbursed')
                ->badge(static fn (): int => Expense::query()->where('status', ExpenseStatus::Reimbursed)->count())
                ->badgeColor(ExpenseStatus::Reimbursed->getColor())
                ->deferBadge()
                ->query(fn ($query) => $query->where('status', ExpenseStatus::Reimbursed)),
        ];
    }
}

<?php

namespace App\Filament\Resources\HR\Expenses\Pages;

use App\Enums\ExpenseStatus;
use App\Filament\Resources\HR\Expenses\ExpenseResource;
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
            null => Tab::make('All'),
            'draft' => Tab::make('Draft')
                ->query(fn ($query) => $query->where('status', ExpenseStatus::Draft)),
            'submitted' => Tab::make('Submitted')
                ->query(fn ($query) => $query->where('status', ExpenseStatus::Submitted)),
            'approved' => Tab::make('Approved')
                ->query(fn ($query) => $query->where('status', ExpenseStatus::Approved)),
            'rejected' => Tab::make('Rejected')
                ->query(fn ($query) => $query->where('status', ExpenseStatus::Rejected)),
            'reimbursed' => Tab::make('Reimbursed')
                ->query(fn ($query) => $query->where('status', ExpenseStatus::Reimbursed)),
        ];
    }
}

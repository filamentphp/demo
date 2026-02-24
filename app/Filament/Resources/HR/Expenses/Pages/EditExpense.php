<?php

namespace App\Filament\Resources\HR\Expenses\Pages;

use App\Filament\Resources\HR\Expenses\ExpenseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditExpense extends EditRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function getActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        /** @var array<int, array<string, mixed>> $lines */
        $lines = $this->data['expenseLines'] ?? [];

        foreach ($lines as &$line) {
            $line['amount'] = (int) ($line['quantity'] ?? 1) * (float) ($line['unit_price'] ?? 0);
        }

        $this->data['expenseLines'] = $lines;
        $data['total_amount'] = collect($lines)
            ->sum(fn (array $line): float => (float) $line['amount']);

        return $data;
    }
}

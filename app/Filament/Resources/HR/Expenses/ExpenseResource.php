<?php

namespace App\Filament\Resources\HR\Expenses;

use App\Enums\ExpenseStatus;
use App\Filament\Resources\HR\Expenses\Pages\CreateExpense;
use App\Filament\Resources\HR\Expenses\Pages\EditExpense;
use App\Filament\Resources\HR\Expenses\Pages\ListExpenses;
use App\Filament\Resources\HR\Expenses\Pages\ViewExpense;
use App\Filament\Resources\HR\Expenses\Schemas\ExpenseForm;
use App\Filament\Resources\HR\Expenses\Schemas\ExpenseInfolist;
use App\Filament\Resources\HR\Expenses\Tables\ExpensesTable;
use App\Filament\Resources\HR\Expenses\Widgets\ExpenseStats;
use App\Models\HR\Expense;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $recordTitleAttribute = 'expense_number';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static string | UnitEnum | null $navigationGroup = 'HR';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'hr/expenses';

    public static function form(Schema $schema): Schema
    {
        return ExpenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpensesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ExpenseInfolist::configure($schema);
    }

    public static function getWidgets(): array
    {
        return [
            ExpenseStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpenses::route('/'),
            'create' => CreateExpense::route('/create'),
            'edit' => EditExpense::route('/{record}/edit'),
            'view' => ViewExpense::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = static::$model;

        return (string) $modelClass::where('status', ExpenseStatus::Submitted)->count();
    }
}

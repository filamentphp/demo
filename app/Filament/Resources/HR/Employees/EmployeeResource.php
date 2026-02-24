<?php

namespace App\Filament\Resources\HR\Employees;

use App\Filament\Resources\HR\Employees\Pages\CreateEmployee;
use App\Filament\Resources\HR\Employees\Pages\EditEmployee;
use App\Filament\Resources\HR\Employees\Pages\ListEmployees;
use App\Filament\Resources\HR\Employees\RelationManagers\LeaveRequestsRelationManager;
use App\Filament\Resources\HR\Employees\RelationManagers\TimesheetsRelationManager;
use App\Filament\Resources\HR\Employees\Schemas\EmployeeForm;
use App\Filament\Resources\HR\Employees\Tables\EmployeesTable;
use App\Filament\Resources\HR\Employees\Widgets\EmployeeStats;
use App\Models\HR\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static string | UnitEnum | null $navigationGroup = 'HR';

    protected static ?int $navigationSort = 0;

    protected static ?string $slug = 'hr/employees';

    public static function form(Schema $schema): Schema
    {
        return EmployeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            TimesheetsRelationManager::class,
            LeaveRequestsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            EmployeeStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'edit' => EditEmployee::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'job_title'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Employee $record */

        return [
            'Department' => optional($record->department)->name,
        ];
    }

    /** @return Builder<Employee> */
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['department']);
    }

    /** @return Builder<Employee> */
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

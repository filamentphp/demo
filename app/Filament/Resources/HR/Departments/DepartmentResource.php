<?php

namespace App\Filament\Resources\HR\Departments;

use App\Filament\Resources\HR\Departments\Pages\ManageDepartments;
use App\Filament\Resources\HR\Departments\RelationManagers\EmployeesRelationManager;
use App\Filament\Resources\HR\Departments\Schemas\DepartmentForm;
use App\Filament\Resources\HR\Departments\Tables\DepartmentsTable;
use App\Models\HR\Department;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string | UnitEnum | null $navigationGroup = 'HR';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'hr/departments';

    public static function form(Schema $schema): Schema
    {
        return DepartmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepartmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            EmployeesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDepartments::route('/'),
        ];
    }
}

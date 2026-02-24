<?php

namespace App\Filament\Resources\HR\Timesheets;

use App\Filament\Resources\HR\Timesheets\Pages\CreateTimesheet;
use App\Filament\Resources\HR\Timesheets\Pages\EditTimesheet;
use App\Filament\Resources\HR\Timesheets\Pages\ListTimesheets;
use App\Filament\Resources\HR\Timesheets\Schemas\TimesheetForm;
use App\Filament\Resources\HR\Timesheets\Tables\TimesheetsTable;
use App\Filament\Resources\HR\Timesheets\Widgets\TimesheetStats;
use App\Models\HR\Timesheet;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TimesheetResource extends Resource
{
    protected static ?string $model = Timesheet::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedClock;

    protected static string | UnitEnum | null $navigationGroup = 'Projects';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'timesheets';

    public static function form(Schema $schema): Schema
    {
        return TimesheetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TimesheetsTable::configure($table);
    }

    public static function getWidgets(): array
    {
        return [
            TimesheetStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTimesheets::route('/'),
            'create' => CreateTimesheet::route('/create'),
            'edit' => EditTimesheet::route('/{record}/edit'),
        ];
    }
}

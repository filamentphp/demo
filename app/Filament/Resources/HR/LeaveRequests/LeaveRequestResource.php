<?php

namespace App\Filament\Resources\HR\LeaveRequests;

use App\Enums\LeaveStatus;
use App\Filament\DemoIconAlias;
use App\Filament\Resources\HR\LeaveRequests\Pages\CreateLeaveRequest;
use App\Filament\Resources\HR\LeaveRequests\Pages\EditLeaveRequest;
use App\Filament\Resources\HR\LeaveRequests\Pages\ListLeaveRequests;
use App\Filament\Resources\HR\LeaveRequests\Pages\ViewLeaveRequest;
use App\Filament\Resources\HR\LeaveRequests\Schemas\LeaveRequestForm;
use App\Filament\Resources\HR\LeaveRequests\Schemas\LeaveRequestInfolist;
use App\Filament\Resources\HR\LeaveRequests\Tables\LeaveRequestsTable;
use App\Models\HR\LeaveRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

/**
 * @extends \Filament\Resources\Resource<LeaveRequest>
 */
class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static string | UnitEnum | null $navigationGroup = 'HR';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'hr/leave-requests';

    public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return FilamentIcon::resolve(DemoIconAlias::RESOURCES_HR_LEAVE_REQUESTS_NAVIGATION) ?? Heroicon::OutlinedCalendarDays;
    }

    public static function form(Schema $schema): Schema
    {
        return LeaveRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LeaveRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveRequestsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeaveRequests::route('/'),
            'create' => CreateLeaveRequest::route('/create'),
            'edit' => EditLeaveRequest::route('/{record}/edit'),
            'view' => ViewLeaveRequest::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = static::$model;

        return (string) $modelClass::where('status', LeaveStatus::Pending)->count();
    }
}

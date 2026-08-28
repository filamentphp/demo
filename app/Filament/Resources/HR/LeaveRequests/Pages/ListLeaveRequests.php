<?php

namespace App\Filament\Resources\HR\LeaveRequests\Pages;

use App\Enums\LeaveStatus;
use App\Filament\Resources\HR\LeaveRequests\LeaveRequestResource;
use App\Models\HR\LeaveRequest;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListLeaveRequests extends ListRecords
{
    protected static string $resource = LeaveRequestResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All')
                ->badge(static fn (): int => LeaveRequest::query()->count())
                ->deferBadge(),
            'pending' => Tab::make('Pending')
                ->badge(static fn (): int => LeaveRequest::query()->where('status', LeaveStatus::Pending)->count())
                ->badgeColor(LeaveStatus::Pending->getColor())
                ->deferBadge()
                ->query(fn ($query) => $query->where('status', LeaveStatus::Pending)),
            'approved' => Tab::make('Approved')
                ->badge(static fn (): int => LeaveRequest::query()->where('status', LeaveStatus::Approved)->count())
                ->badgeColor(LeaveStatus::Approved->getColor())
                ->deferBadge()
                ->query(fn ($query) => $query->where('status', LeaveStatus::Approved)),
            'rejected' => Tab::make('Rejected')
                ->badge(static fn (): int => LeaveRequest::query()->where('status', LeaveStatus::Rejected)->count())
                ->badgeColor(LeaveStatus::Rejected->getColor())
                ->deferBadge()
                ->query(fn ($query) => $query->where('status', LeaveStatus::Rejected)),
            'taken' => Tab::make('Taken')
                ->badge(static fn (): int => LeaveRequest::query()->where('status', LeaveStatus::Taken)->count())
                ->badgeColor(LeaveStatus::Taken->getColor())
                ->deferBadge()
                ->query(fn ($query) => $query->where('status', LeaveStatus::Taken)),
            'cancelled' => Tab::make('Cancelled')
                ->badge(static fn (): int => LeaveRequest::query()->where('status', LeaveStatus::Cancelled)->count())
                ->badgeColor(LeaveStatus::Cancelled->getColor())
                ->deferBadge()
                ->query(fn ($query) => $query->where('status', LeaveStatus::Cancelled)),
        ];
    }
}

<?php

namespace App\Filament\Resources\HR\LeaveRequests\Pages;

use App\Enums\LeaveStatus;
use App\Filament\Resources\HR\LeaveRequests\LeaveRequestResource;
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
            null => Tab::make('All'),
            'pending' => Tab::make('Pending')
                ->query(fn ($query) => $query->where('status', LeaveStatus::Pending)),
            'approved' => Tab::make('Approved')
                ->query(fn ($query) => $query->where('status', LeaveStatus::Approved)),
            'rejected' => Tab::make('Rejected')
                ->query(fn ($query) => $query->where('status', LeaveStatus::Rejected)),
            'taken' => Tab::make('Taken')
                ->query(fn ($query) => $query->where('status', LeaveStatus::Taken)),
        ];
    }
}

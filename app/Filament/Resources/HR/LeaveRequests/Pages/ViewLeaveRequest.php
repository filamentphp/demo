<?php

namespace App\Filament\Resources\HR\LeaveRequests\Pages;

use App\Enums\LeaveStatus;
use App\Filament\Resources\HR\LeaveRequests\LeaveRequestResource;
use App\Models\HR\LeaveRequest;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class ViewLeaveRequest extends ViewRecord
{
    protected static string $resource = LeaveRequestResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('approve')
                ->icon(Heroicon::Check)
                ->color('success')
                ->visible(fn (LeaveRequest $record): bool => $record->status === LeaveStatus::Pending)
                ->requiresConfirmation()
                ->action(function (LeaveRequest $record): void {
                    $record->update([
                        'status' => LeaveStatus::Approved,
                        'reviewed_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Leave request approved')
                        ->success()
                        ->send();

                    $this->refreshFormData(['status']);
                }),
            Action::make('reject')
                ->icon(Heroicon::XMark)
                ->color('danger')
                ->visible(fn (LeaveRequest $record): bool => $record->status === LeaveStatus::Pending)
                ->modalWidth(Width::Medium)
                ->modalSubmitActionLabel('Reject')
                ->schema([
                    Textarea::make('reviewer_notes')
                        ->label('Reason for rejection')
                        ->required(),
                ])
                ->action(function (LeaveRequest $record, array $data): void {
                    $record->update([
                        'status' => LeaveStatus::Rejected,
                        'reviewer_notes' => $data['reviewer_notes'],
                        'reviewed_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Leave request rejected')
                        ->danger()
                        ->send();

                    $this->refreshFormData(['status']);
                }),
            EditAction::make(),
        ];
    }
}

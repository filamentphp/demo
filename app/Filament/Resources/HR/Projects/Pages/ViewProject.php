<?php

namespace App\Filament\Resources\HR\Projects\Pages;

use App\Enums\ProjectStatus;
use App\Filament\Resources\HR\Projects\ProjectResource;
use App\Models\HR\Project;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('change_status')
                ->icon(Heroicon::ArrowPathRoundedSquare)
                ->color('primary')
                ->modalWidth(Width::Medium)
                ->modalSubmitActionLabel('Save')
                ->stickyModalFooter()
                ->fillForm(fn (Project $record): array => [
                    'status' => $record->status,
                ])
                ->schema([
                    ToggleButtons::make('status')
                        ->options(ProjectStatus::class)
                        ->inline()
                        ->required(),
                ])
                ->action(function (Project $record, array $data): void {
                    $record->update($data);
                    $this->refreshFormData(['status']);
                }),
            Action::make('put_on_hold')
                ->icon(Heroicon::Pause)
                ->color('warning')
                ->visible(fn (Project $record): bool => $record->status === ProjectStatus::Active)
                ->requiresConfirmation()
                ->modalHeading('Put Project On Hold')
                ->modalDescription('This will pause all work on this project.')
                ->modalIcon(Heroicon::ExclamationTriangle)
                ->modalIconColor('warning')
                ->action(function (Project $record): void {
                    $record->update(['status' => ProjectStatus::OnHold]);
                    $this->refreshFormData(['status']);

                    Notification::make()
                        ->title('Project put on hold')
                        ->warning()
                        ->send();
                }),
            Action::make('resume')
                ->icon(Heroicon::Play)
                ->color('success')
                ->visible(fn (Project $record): bool => $record->status === ProjectStatus::OnHold)
                ->action(function (Project $record): void {
                    $record->update(['status' => ProjectStatus::Active]);
                    $this->refreshFormData(['status']);

                    Notification::make()
                        ->title('Project resumed')
                        ->success()
                        ->send();
                }),
            Action::make('complete')
                ->icon(Heroicon::CheckCircle)
                ->color('success')
                ->visible(fn (Project $record): bool => in_array($record->status, [ProjectStatus::Active, ProjectStatus::OnHold]))
                ->requiresConfirmation()
                ->action(function (Project $record): void {
                    $record->update([
                        'status' => ProjectStatus::Completed,
                        'end_date' => now(),
                    ]);
                    $this->refreshFormData(['status', 'end_date']);

                    Notification::make()
                        ->title('Project completed')
                        ->success()
                        ->send();
                }),
            EditAction::make(),
        ];
    }
}

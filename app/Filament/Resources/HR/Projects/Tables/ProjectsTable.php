<?php

namespace App\Filament\Resources\HR\Projects\Tables;

use App\Enums\ProjectStatus;
use App\Enums\TaskPriority;
use App\Models\HR\Project;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('priority')
                    ->badge(),

                TextColumn::make('department.name')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('No department'),

                TextColumn::make('budget')
                    ->money('usd')
                    ->sortable()
                    ->summarize(Sum::make()->money('usd')),

                TextColumn::make('spent')
                    ->money('usd')
                    ->sortable()
                    ->summarize(Sum::make()->money('usd')),

                TextColumn::make('progress')
                    ->state(fn (Project $record): string => $record->estimated_hours > 0
                        ? number_format(($record->actual_hours / $record->estimated_hours) * 100, 0) . '%'
                        : '0%'),

                TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ProjectStatus::class),

                SelectFilter::make('priority')
                    ->options(TaskPriority::class),

                SelectFilter::make('department')
                    ->relationship('department', 'name'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
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
                        ->action(fn (Project $record, array $data) => $record->update($data)),
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

                            Notification::make()
                                ->title('Project completed')
                                ->success()
                                ->send();
                        }),
                    DeleteAction::make()
                        ->action(function (): void {
                            Notification::make()
                                ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                                ->warning()
                                ->send();
                        }),
                ]),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->action(function (): void {
                        Notification::make()
                            ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                            ->warning()
                            ->send();
                    }),
            ]);
    }
}

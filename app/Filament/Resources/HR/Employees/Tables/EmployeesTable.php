<?php

namespace App\Filament\Resources\HR\Employees\Tables;

use App\Enums\EmploymentType;
use App\Models\HR\Employee;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('department.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('No department'),

                TextColumn::make('job_title')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('employment_type')
                    ->badge(),

                TextColumn::make('salary')
                    ->money('usd')
                    ->sortable()
                    ->toggleable(),

                ColorColumn::make('team_color')
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                TextColumn::make('hire_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('employment_type')
                    ->options(EmploymentType::class),

                SelectFilter::make('department')
                    ->relationship('department', 'name'),

                TernaryFilter::make('is_active'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('view_profile')
                        ->icon(Heroicon::Eye)
                        ->color('primary')
                        ->slideOver()
                        ->schema([
                            TextEntry::make('name'),
                            TextEntry::make('email'),
                            TextEntry::make('phone'),
                            TextEntry::make('department.name')
                                ->placeholder('No department'),
                            TextEntry::make('job_title'),
                            TextEntry::make('employment_type')
                                ->badge(),
                            TextEntry::make('salary')
                                ->money('usd'),
                            TextEntry::make('hire_date')
                                ->date(),
                            IconEntry::make('is_active')
                                ->boolean(),
                        ])
                        ->modalSubmitAction(false),
                    EditAction::make(),
                    Action::make('toggle_active')
                        ->icon(fn (Employee $record): Heroicon => $record->is_active ? Heroicon::XMark : Heroicon::Check)
                        ->label(fn (Employee $record): string => $record->is_active ? 'Deactivate' : 'Activate')
                        ->color(fn (Employee $record): string => $record->is_active ? 'danger' : 'success')
                        ->requiresConfirmation()
                        ->action(fn (Employee $record) => $record->update(['is_active' => ! $record->is_active])),
                    Action::make('change_department')
                        ->icon(Heroicon::BuildingOffice2)
                        ->color('primary')
                        ->modalWidth(Width::Medium)
                        ->modalSubmitActionLabel('Save')
                        ->fillForm(fn (Employee $record): array => [
                            'department_id' => $record->department_id,
                        ])
                        ->schema([
                            Select::make('department_id')
                                ->relationship('department', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])
                        ->action(fn (Employee $record, array $data) => $record->update($data)),
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
                BulkAction::make('change_department')
                    ->icon(Heroicon::BuildingOffice2)
                    ->color('primary')
                    ->schema([
                        Select::make('department_id')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->action(function (Collection $records, array $data): void {
                        $records->each(fn (Employee $record) => $record->update($data));
                    })
                    ->deselectRecordsAfterCompletion(),
                BulkAction::make('toggle_active')
                    ->icon(Heroicon::Power)
                    ->color('warning')
                    ->schema([
                        ToggleButtons::make('is_active')
                            ->options([
                                '1' => 'Active',
                                '0' => 'Inactive',
                            ])
                            ->inline()
                            ->required(),
                    ])
                    ->action(function (Collection $records, array $data): void {
                        $records->each(fn (Employee $record) => $record->update(['is_active' => (bool) $data['is_active']]));
                    })
                    ->deselectRecordsAfterCompletion(),
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

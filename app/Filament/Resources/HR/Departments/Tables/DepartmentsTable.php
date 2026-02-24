<?php

namespace App\Filament\Resources\HR\Departments\Tables;

use App\Models\HR\Department;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class DepartmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),

                TextColumn::make('parent.name')
                    ->placeholder('Top Level'),

                TextColumn::make('employees_count')
                    ->counts('employees')
                    ->label('Headcount'),

                TextColumn::make('budget')
                    ->money('usd')
                    ->sortable(),

                ColorColumn::make('color')
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    Action::make('adjust_budget')
                        ->icon(Heroicon::Banknotes)
                        ->color('success')
                        ->modalWidth(Width::ExtraSmall)
                        ->modalSubmitActionLabel('Save')
                        ->fillForm(fn (Department $record): array => [
                            'budget' => $record->budget,
                        ])
                        ->schema([
                            TextInput::make('budget')
                                ->numeric()
                                ->prefix('$')
                                ->minValue(0)
                                ->maxValue(9999999999.99)
                                ->required(),
                        ])
                        ->action(fn (Department $record, array $data) => $record->update($data)),
                    Action::make('toggle_active')
                        ->icon(fn (Department $record): Heroicon => $record->is_active ? Heroicon::XMark : Heroicon::Check)
                        ->label(fn (Department $record): string => $record->is_active ? 'Deactivate' : 'Activate')
                        ->color(fn (Department $record): string => $record->is_active ? 'danger' : 'success')
                        ->action(fn (Department $record) => $record->update(['is_active' => ! $record->is_active])),
                    ReplicateAction::make()
                        ->requiresConfirmation()
                        ->excludeAttributes(['id', 'employees_count'])
                        ->beforeReplicaSaved(function (Department $replica): void {
                            $replica->slug = Str::slug($replica->name) . '-' . Str::random(5);
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
            ])
            ->recordClasses(fn ($record) => match (true) {
                $record->employees_count > $record->headcount_limit && $record->headcount_limit > 0 => 'bg-danger-50 dark:bg-danger-950/50',
                default => null,
            });
    }
}

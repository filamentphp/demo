<?php

namespace App\Filament\Resources\HR\Projects\RelationManagers;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')
                    ->required(),

                Select::make('assigned_to')
                    ->relationship('assignee', 'name')
                    ->searchable()
                    ->preload(),

                ToggleButtons::make('status')
                    ->options(TaskStatus::class)
                    ->inline()
                    ->required()
                    ->default(TaskStatus::Backlog),

                ToggleButtons::make('priority')
                    ->options(TaskPriority::class)
                    ->inline()
                    ->required()
                    ->default(TaskPriority::Medium),

                TextInput::make('estimated_hours')
                    ->numeric()
                    ->step(0.5)
                    ->minValue(0)
                    ->maxValue(99999.9)
                    ->suffix('hours'),

                DatePicker::make('due_date'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),

                TextColumn::make('assignee.name')
                    ->placeholder('Unassigned')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('priority')
                    ->badge(),

                TextColumn::make('estimated_hours')
                    ->numeric(1)
                    ->sortable(),

                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->action(function (): void {
                        Notification::make()
                            ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                            ->warning()
                            ->send();
                    }),
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

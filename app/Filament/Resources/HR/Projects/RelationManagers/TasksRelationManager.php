<?php

namespace App\Filament\Resources\HR\Projects\RelationManagers;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Filament\Resources\HR\Tasks\Tables\TasksTable;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
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
        return TasksTable::configure($table, projectScoped: true)
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}

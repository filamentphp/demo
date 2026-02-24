<?php

namespace App\Filament\Resources\HR\Tasks\Schemas;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Task Details')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('assigned_to')
                            ->relationship('assignee', 'name')
                            ->searchable()
                            ->preload(),

                        ToggleButtons::make('status')
                            ->options(TaskStatus::class)
                            ->inline()
                            ->required()
                            ->live()
                            ->default(TaskStatus::Backlog)
                            ->columnSpanFull(),

                        Radio::make('priority')
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

                        RichEditor::make('description')
                            ->columnSpanFull(),

                        CheckboxList::make('labels')
                            ->options([
                                'bug' => 'Bug',
                                'feature' => 'Feature',
                                'enhancement' => 'Enhancement',
                                'documentation' => 'Documentation',
                                'design' => 'Design',
                                'testing' => 'Testing',
                                'refactor' => 'Refactor',
                                'urgent' => 'Urgent',
                            ])
                            ->columns(4)
                            ->columnSpanFull(),

                        DateTimePicker::make('completed_at')
                            ->visible(fn (Get $get): bool => in_array($get('status'), [
                                TaskStatus::Completed,
                                TaskStatus::Completed->value,
                            ])),
                    ]),
            ]);
    }
}

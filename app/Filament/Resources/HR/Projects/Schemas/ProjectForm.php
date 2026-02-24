<?php

namespace App\Filament\Resources\HR\Projects\Schemas;

use App\Enums\ProjectStatus;
use App\Enums\TaskPriority;
use App\Models\HR\Employee;
use App\Models\HR\Project;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Project')
                    ->schema([
                        Tab::make('Overview')
                            ->icon(Heroicon::InformationCircle)
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, $state, Set $set): void {
                                        if ($operation !== 'create') {
                                            return;
                                        }

                                        $set('slug', Str::slug($state));
                                    }),

                                TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Project::class, 'slug', ignoreRecord: true),

                                RichEditor::make('description')
                                    ->columnSpanFull(),

                                Select::make('department_id')
                                    ->relationship('department', 'name')
                                    ->searchable()
                                    ->preload(),

                                ToggleButtons::make('status')
                                    ->options(ProjectStatus::class)
                                    ->inline()
                                    ->required()
                                    ->default(ProjectStatus::Planning),

                                ToggleButtons::make('priority')
                                    ->options(TaskPriority::class)
                                    ->inline()
                                    ->required()
                                    ->default(TaskPriority::Medium),

                                ColorPicker::make('color'),

                                DatePicker::make('start_date')
                                    ->required(),

                                DatePicker::make('end_date')
                                    ->minDate(fn (Get $get) => $get('start_date')),
                            ]),

                        Tab::make('Plan')
                            ->icon(Heroicon::ClipboardDocumentList)
                            ->schema([
                                Builder::make('plan')
                                    ->hiddenLabel()
                                    ->blocks([
                                        Block::make('milestone')
                                            ->icon(Heroicon::Flag)
                                            ->schema([
                                                TextInput::make('title')
                                                    ->required(),
                                                DatePicker::make('target_date')
                                                    ->required(),
                                                Textarea::make('description')
                                                    ->rows(2),
                                            ]),

                                        Block::make('task_group')
                                            ->label('Task group')
                                            ->icon(Heroicon::ListBullet)
                                            ->schema([
                                                TextInput::make('title')
                                                    ->required(),
                                                Select::make('assignee')
                                                    ->options(fn () => Employee::pluck('name', 'id')),
                                                TagsInput::make('tasks')
                                                    ->placeholder('Add tasks'),
                                            ]),

                                        Block::make('checkpoint')
                                            ->icon(Heroicon::CheckCircle)
                                            ->schema([
                                                TextInput::make('title')
                                                    ->required(),
                                                DatePicker::make('date')
                                                    ->required(),
                                                Select::make('status')
                                                    ->options([
                                                        'pending' => 'Pending',
                                                        'passed' => 'Passed',
                                                        'failed' => 'Failed',
                                                    ]),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Budget')
                            ->icon(Heroicon::CurrencyDollar)
                            ->columns(2)
                            ->schema([
                                TextInput::make('budget')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0)
                                    ->maxValue(9999999999.99)
                                    ->default(0),

                                TextInput::make('spent')
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0)
                                    ->maxValue(9999999999.99)
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->default(0),

                                TextInput::make('estimated_hours')
                                    ->numeric()
                                    ->suffix('hours')
                                    ->minValue(0)
                                    ->maxValue(9999999.9)
                                    ->required()
                                    ->default(0),

                                TextInput::make('actual_hours')
                                    ->numeric()
                                    ->suffix('hours')
                                    ->minValue(0)
                                    ->maxValue(9999999.9)
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->default(0),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}

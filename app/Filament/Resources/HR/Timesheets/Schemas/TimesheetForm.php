<?php

namespace App\Filament\Resources\HR\Timesheets\Schemas;

use App\Models\HR\Employee;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class TimesheetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Timesheet Entry')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('employee_id')
                            ->relationship('employee', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set): void {
                                if ($state) {
                                    $employee = Employee::query()->find($state);
                                    if ($employee instanceof Employee && $employee->hourly_rate) {
                                        $set('hourly_rate', $employee->hourly_rate);
                                    }
                                }
                            }),

                        Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live(),

                        Select::make('task_id')
                            ->relationship('task', 'title', fn ($query, Get $get) => $query->when(
                                $get('project_id'),
                                fn ($q, $projectId) => $q->where('project_id', $projectId)
                            ))
                            ->searchable()
                            ->preload(),

                        DatePicker::make('date')
                            ->required()
                            ->default(now()),

                        TextInput::make('hours')
                            ->numeric()
                            ->step(0.5)
                            ->minValue(0)
                            ->maxValue(999.9)
                            ->suffix('hours')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set): void {
                                static::calculateTotalCost($get, $set);
                            }),

                        TextInput::make('minutes')
                            ->required()
                            ->integer()
                            ->minValue(0)
                            ->maxValue(59)
                            ->mask('99')
                            ->suffix('min')
                            ->default(0),

                        Toggle::make('is_billable')
                            ->label('Billable')
                            ->default(true)
                            ->columnSpanFull(),

                        TextInput::make('hourly_rate')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->maxValue(999999.99)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set): void {
                                static::calculateTotalCost($get, $set);
                            }),

                        TextInput::make('total_cost')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->maxValue(99999999.99)
                            ->disabled()
                            ->dehydrated(),

                        Textarea::make('description')
                            ->rows(2)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    protected static function calculateTotalCost(Get $get, Set $set): void
    {
        $hours = (float) ($get('hours') ?? 0);
        $rate = (float) ($get('hourly_rate') ?? 0);
        $set('total_cost', number_format($hours * $rate, 2, '.', ''));
    }
}

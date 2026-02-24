<?php

namespace App\Filament\Resources\HR\LeaveRequests\Schemas;

use App\Enums\LeaveStatus;
use App\Enums\LeaveType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Leave Details')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('employee_id')
                            ->relationship('employee', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        ToggleButtons::make('type')
                            ->options(LeaveType::class)
                            ->inline()
                            ->required()
                            ->columnSpanFull(),

                        DatePicker::make('start_date')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set): void {
                                static::calculateDays($get, $set);
                            }),

                        DatePicker::make('end_date')
                            ->required()
                            ->minDate(fn (Get $get) => $get('start_date'))
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set): void {
                                static::calculateDays($get, $set);
                            }),

                        TimePicker::make('start_time')
                            ->seconds(false)
                            ->label('Start time (half days)'),

                        TimePicker::make('end_time')
                            ->seconds(false)
                            ->label('End time (half days)'),

                        TextInput::make('days_requested')
                            ->numeric()
                            ->step(0.5)
                            ->minValue(0)
                            ->maxValue(999.9)
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                        Textarea::make('reason')
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),

                Section::make('Review')
                    ->hiddenOn('create')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('notice')
                            ->state('Note: Approving this request will deduct days from the employee\'s leave balance.')
                            ->columnSpanFull(),

                        ToggleButtons::make('status')
                            ->options(LeaveStatus::class)
                            ->inline()
                            ->required()
                            ->columnSpanFull(),

                        Select::make('approver_id')
                            ->relationship('approver', 'name')
                            ->searchable(),

                        Textarea::make('reviewer_notes')
                            ->maxLength(65535),
                    ]),
            ]);
    }

    protected static function calculateDays(Get $get, Set $set): void
    {
        $startDate = $get('start_date');
        $endDate = $get('end_date');

        if ($startDate && $endDate) {
            $days = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
            $set('days_requested', max(0.5, $days));
        }
    }
}

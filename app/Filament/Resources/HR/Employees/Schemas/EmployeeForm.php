<?php

namespace App\Filament\Resources\HR\Employees\Schemas;

use App\Enums\EmploymentType;
use App\Models\HR\Employee;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Employee')
                    ->schema([
                        Tab::make('Personal')
                            ->icon(Heroicon::User)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->required()
                                    ->email()
                                    ->maxLength(255)
                                    ->unique(Employee::class, 'email', ignoreRecord: true),

                                TextInput::make('phone')
                                    ->tel()
                                    ->mask('(999) 999-9999')
                                    ->maxLength(255),

                                DatePicker::make('date_of_birth')
                                    ->maxDate(now()),

                                ColorPicker::make('team_color')
                                    ->hex(),

                                CheckboxList::make('skills')
                                    ->options([
                                        'PHP' => 'PHP',
                                        'Laravel' => 'Laravel',
                                        'JavaScript' => 'JavaScript',
                                        'TypeScript' => 'TypeScript',
                                        'React' => 'React',
                                        'Vue.js' => 'Vue.js',
                                        'Python' => 'Python',
                                        'SQL' => 'SQL',
                                        'Docker' => 'Docker',
                                        'AWS' => 'AWS',
                                    ])
                                    ->columns(5)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Tab::make('Employment')
                            ->icon(Heroicon::Briefcase)
                            ->schema([
                                Select::make('department_id')
                                    ->relationship('department', 'name')
                                    ->searchable()
                                    ->preload(),

                                TextInput::make('job_title')
                                    ->required()
                                    ->maxLength(255),

                                ToggleButtons::make('employment_type')
                                    ->options(EmploymentType::class)
                                    ->inline()
                                    ->required()
                                    ->live()
                                    ->default(EmploymentType::FullTime)
                                    ->columnSpanFull(),

                                DatePicker::make('hire_date')
                                    ->required()
                                    ->default(now()),

                                TextInput::make('salary')
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0)
                                    ->maxValue(99999999.99)
                                    ->visible(fn (Get $get): bool => in_array($get('employment_type'), [
                                        EmploymentType::FullTime,
                                        EmploymentType::PartTime,
                                        EmploymentType::FullTime->value,
                                        EmploymentType::PartTime->value,
                                    ])),

                                TextInput::make('hourly_rate')
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0)
                                    ->maxValue(999999.99)
                                    ->visible(fn (Get $get): bool => in_array($get('employment_type'), [
                                        EmploymentType::Contractor,
                                        EmploymentType::Intern,
                                        EmploymentType::Contractor->value,
                                        EmploymentType::Intern->value,
                                    ])),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true)
                                    ->columnStart(1),
                            ])
                            ->columns(2),

                        Tab::make('Documents & Metadata')
                            ->icon(Heroicon::DocumentText)
                            ->schema([
                                KeyValue::make('metadata')
                                    ->keyLabel('Property')
                                    ->valueLabel('Value')
                                    ->reorderable()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\HR\Expenses\Schemas;

use App\Enums\ExpenseCategory;
use App\Enums\ExpenseStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('expense_number')
                            ->default(fn () => 'EXP-' . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT))
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255),

                        Select::make('employee_id')
                            ->relationship('employee', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload(),

                        ToggleButtons::make('category')
                            ->options(ExpenseCategory::class)
                            ->inline()
                            ->required()
                            ->columnSpanFull(),

                        ToggleButtons::make('status')
                            ->options(ExpenseStatus::class)
                            ->inline()
                            ->required()
                            ->default(ExpenseStatus::Draft)
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),

                Section::make('Line Items')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('expenseLines')
                            ->hiddenLabel()
                            ->relationship()
                            ->defaultItems(1)
                            ->table([
                                TableColumn::make('Description'),
                                TableColumn::make('Quantity')
                                    ->width(100),
                                TableColumn::make('Unit Price')
                                    ->width(120),
                                TableColumn::make('Amount')
                                    ->width(120),
                                TableColumn::make('Date')
                                    ->width(150),
                            ])
                            ->schema([
                                TextInput::make('description')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('quantity')
                                    ->integer()
                                    ->minValue(1)
                                    ->maxValue(2147483647)
                                    ->default(1)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set): void {
                                        $quantity = (int) ($get('quantity') ?? 1);
                                        $unitPrice = (float) ($get('unit_price') ?? 0);
                                        $set('amount', number_format($quantity * $unitPrice, 2, '.', ''));
                                    }),

                                TextInput::make('unit_price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0)
                                    ->maxValue(99999999.99)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set): void {
                                        $quantity = (int) ($get('quantity') ?? 1);
                                        $unitPrice = (float) ($get('unit_price') ?? 0);
                                        $set('amount', number_format($quantity * $unitPrice, 2, '.', ''));
                                    }),

                                TextInput::make('amount')
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0)
                                    ->maxValue(99999999.99)
                                    ->disabled()
                                    ->dehydrated(),

                                DatePicker::make('date')
                                    ->required()
                                    ->default(now()),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make('Summary')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('total_amount')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->maxValue(99999999.99)
                            ->disabled()
                            ->dehydrated(),

                        Select::make('currency')
                            ->required()
                            ->options([
                                'USD' => 'USD',
                                'EUR' => 'EUR',
                                'GBP' => 'GBP',
                                'CAD' => 'CAD',
                            ])
                            ->default('USD'),

                        FileUpload::make('receipt_path')
                            ->directory('expense-receipts'),

                        Textarea::make('notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

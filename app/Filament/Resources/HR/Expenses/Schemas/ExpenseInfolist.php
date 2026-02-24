<?php

namespace App\Filament\Resources\HR\Expenses\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExpenseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Expense Details')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('expense_number'),
                        TextEntry::make('employee.name'),
                        TextEntry::make('category')
                            ->badge(),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('total_amount')
                            ->money('usd'),
                        TextEntry::make('currency'),
                        TextEntry::make('project.name')
                            ->placeholder('No project'),
                        TextEntry::make('submitted_at')
                            ->dateTime()
                            ->placeholder('Not submitted'),
                        TextEntry::make('approved_at')
                            ->dateTime()
                            ->placeholder('Not approved'),
                        TextEntry::make('description')
                            ->columnSpanFull(),
                        TextEntry::make('notes')
                            ->placeholder('No notes')
                            ->columnSpanFull(),
                    ]),

                Section::make('Line Items')
                    ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('expenseLines')
                            ->hiddenLabel()
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
                                TextEntry::make('description'),
                                TextEntry::make('quantity'),
                                TextEntry::make('unit_price')
                                    ->money('usd'),
                                TextEntry::make('amount')
                                    ->money('usd'),
                                TextEntry::make('date')
                                    ->date(),
                            ]),
                    ]),
            ]);
    }
}

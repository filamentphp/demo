<?php

namespace App\Filament\Resources\HR\Projects\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TimesheetsRelationManager extends RelationManager
{
    protected static string $relationship = 'timesheets';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->sortable()
                    ->weight(FontWeight::Medium),

                TextColumn::make('date')
                    ->date()
                    ->sortable(),

                TextColumn::make('hours')
                    ->numeric(1)
                    ->sortable()
                    ->summarize(Sum::make()->label('Total hours')),

                IconColumn::make('is_billable')
                    ->label('Billable')
                    ->boolean(),

                TextColumn::make('total_cost')
                    ->money('usd')
                    ->sortable()
                    ->summarize(Sum::make()->money('usd')->label('Total cost')),
            ])
            ->defaultSort('date', 'desc');
    }
}

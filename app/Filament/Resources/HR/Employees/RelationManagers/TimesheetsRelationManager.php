<?php

namespace App\Filament\Resources\HR\Employees\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TimesheetsRelationManager extends RelationManager
{
    protected static string $relationship = 'timesheets';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.name')
                    ->sortable()
                    ->weight(FontWeight::Medium),

                TextColumn::make('date')
                    ->date()
                    ->sortable(),

                TextColumn::make('hours')
                    ->numeric(1)
                    ->sortable(),

                IconColumn::make('is_billable')
                    ->label('Billable')
                    ->boolean(),

                TextColumn::make('total_cost')
                    ->money('usd')
                    ->sortable(),
            ])
            ->defaultSort('date', 'desc');
    }
}

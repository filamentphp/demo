<?php

namespace App\Filament\Resources\HR\Employees\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeaveRequestsRelationManager extends RelationManager
{
    protected static string $relationship = 'leaveRequests';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->weight(FontWeight::Medium),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('days_requested')
                    ->numeric(1)
                    ->sortable(),
            ])
            ->defaultSort('start_date', 'desc');
    }
}

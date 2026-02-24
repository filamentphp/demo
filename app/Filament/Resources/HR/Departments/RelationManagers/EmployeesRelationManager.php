<?php

namespace App\Filament\Resources\HR\Departments\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('job_title')
                    ->sortable(),

                TextColumn::make('employment_type')
                    ->badge(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ]);
    }
}

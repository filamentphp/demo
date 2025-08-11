<?php

namespace App\Filament\Clusters\Products\Resources\BrandResource\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Squire\Models\Country;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    protected static ?string $recordTitleAttribute = 'full_address';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('street'),

                TextInput::make('zip'),

                TextInput::make('city'),

                TextInput::make('state'),

                Select::make('country')
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $query) => Country::where('name', 'like', "%{$query}%")->pluck('name', 'id'))
                    ->getOptionLabelUsing(fn ($value): ?string => Country::firstWhere('id', $value)?->getAttribute('name')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('street'),

                TextColumn::make('zip'),

                TextColumn::make('city'),

                TextColumn::make('country')
                    ->formatStateUsing(fn ($state): ?string => Country::find($state)->name ?? null),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make(),
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
                DeleteAction::make(),
            ])
            ->groupedBulkActions([
                DetachBulkAction::make(),
                DeleteBulkAction::make(),
            ]);
    }
}

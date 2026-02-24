<?php

namespace App\Filament\Resources\Shop\Customers\RelationManagers;

use App\Enums\CountryCode;
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
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                    ->options(CountryCode::class)
                    ->searchable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('street')
                    ->weight(FontWeight::Medium),

                TextColumn::make('zip'),

                TextColumn::make('city'),

                TextColumn::make('country'),
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

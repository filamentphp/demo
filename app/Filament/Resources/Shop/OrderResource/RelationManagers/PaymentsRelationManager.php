<?php

namespace App\Filament\Resources\Shop\OrderResource\RelationManagers;

use Akaunting\Money\Currency;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $recordTitleAttribute = 'reference';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('reference')
                    ->columnSpan('full')
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                    ->required(),

                Forms\Components\Select::make('currency')
                    ->options(collect(Currency::getCurrencies())->mapWithKeys(fn ($item, $key) => [$key => data_get($item, 'name')]))
                    ->searchable()
                    ->required(),

                Forms\Components\ToggleButtons::make('provider')
                    ->inline()
                    ->grouped()
                    ->options([
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                    ])
                    ->required(),

                Forms\Components\ToggleButtons::make('method')
                    ->inline()
                    ->options([
                        'credit_card' => 'Credit card',
                        'bank_transfer' => 'Bank transfer',
                        'paypal' => 'PayPal',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ColumnGroup::make('Details')
                    ->columns([
                        Tables\Columns\TextColumn::make('reference')
                            ->searchable(),

                        Tables\Columns\TextColumn::make('amount')
                            ->sortable()
                            ->money(fn ($record) => $record->currency),
                    ]),

                Tables\Columns\ColumnGroup::make('Context')
                    ->columns([
                        Tables\Columns\TextColumn::make('provider')
                            ->formatStateUsing(fn ($state) => Str::headline($state))
                            ->sortable(),

                        Tables\Columns\TextColumn::make('method')
                            ->formatStateUsing(fn ($state) => Str::headline($state))
                            ->sortable(),
                    ]),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}

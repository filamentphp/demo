<?php

namespace App\Filament\Resources\Shop\CustomerResource\RelationManagers;

use App\Filament\Resources\Shop\OrderResource;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\HasManyThroughRelationManager;
use Filament\Tables;
use Filament\Resources\Table;
use Illuminate\Support\Str;

class PaymentsRelationManager extends HasManyThroughRelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('reference')
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->required(),

                Forms\Components\Select::make('provider')
                    ->options([
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                    ])
                    ->required(),

                Forms\Components\Select::make('method')
                    ->options([
                        'credit_card' => 'Credit card',
                        'bank_transfer' => 'Bank transfer',
                        'paypal' => 'PayPal',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.number')
                    ->url(fn ($record) => OrderResource::getUrl('edit', [$record]))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('reference')
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->sortable()
                    ->money(),

                Tables\Columns\TextColumn::make('provider')
                    ->formatStateUsing(fn ($state) => Str::headline($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('method')
                    ->formatStateUsing(fn ($state) => Str::headline($state))
                    ->sortable(),
            ])
            ->filters([
                //
            ]);
    }
}
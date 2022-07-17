<?php

namespace App\Filament\Resources\Shop\CustomerResource\RelationManagers;

use Akaunting\Money\Currency;
use App\Filament\Resources\Shop\OrderResource;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\HasManyThroughRelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PaymentsRelationManager extends HasManyThroughRelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->label('Order')
                    ->relationship(
                        'order',
                        'number',
                        fn (Builder $query, HasManyThroughRelationManager $livewire) => $query->whereBelongsTo($livewire->ownerRecord)
                    )
                    ->searchable()
                    ->hidden(fn (HasManyThroughRelationManager $livewire) => $livewire->mountedTableAction === 'edit')
                    ->required(),

                Forms\Components\TextInput::make('reference')
                    ->columnSpan(fn (HasManyThroughRelationManager $livewire) => $livewire->mountedTableAction === 'edit' ? 2 : 1)
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('currency')
                    ->options(collect(Currency::getCurrencies())->mapWithKeys(fn ($item, $key) => [$key => data_get($item, 'name')]))
                    ->searchable()
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
                    ->url(fn ($record) => OrderResource::getUrl('edit', [$record->order]))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('reference')
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->sortable()
                    ->money(fn ($record) => $record->currency),

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

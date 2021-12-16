<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\DiscountResource\Pages;
use App\Filament\Resources\Shop\DiscountResource\RelationManagers;
use App\Models\Shop\Discount;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

final class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $slug = 'shop/discounts';

    protected static ?string $recordTitleAttribute = 'code';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationIcon = 'heroicon-o-receipt-tax';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('code')
                                            ->helperText('Customers will enter this discount code at checkout.')
                                            ->required()
                                            ->unique(Discount::class, 'code', fn ($record) => $record)
                                            ->columnSpan(2),
                                        Forms\Components\Radio::make('type')
                                            ->label('Type')
                                            ->options([
                                                'percentage' => 'Percentage',
                                                'fixed' => 'Fixed',
                                            ])
                                            ->required(),
                                        Forms\Components\TextInput::make('value')
                                            ->numeric()
                                            ->required(),
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Placeholder::make('Visibility'),
                                                Forms\Components\Toggle::make('is_visible')
                                                    ->label('Setup discount visibility for the customers.')
                                                    ->default(true)
                                                    ->inline(),
                                            ])->columnSpan(2),
                                        Forms\Components\DatePicker::make('starts_at')
                                            ->default(now()->addDays(1))
                                            ->required(),
                                        Forms\Components\DatePicker::make('ends_at')
                                            ->required(),
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Placeholder::make('Usage limits'),
                                                Forms\Components\Checkbox::make('usage_limit_enabled')
                                                    ->label('Limit number of times this discount can be used in total')
                                                    ->inline()
                                                    ->reactive()
                                                    ->afterStateUpdated(fn (callable $set) => $set('usage_limit', '')),
                                                Forms\Components\Grid::make()
                                                    ->schema([
                                                        Forms\Components\TextInput::make('usage_limit')
                                                            ->numeric()
                                                            ->required()
                                                            ->disableLabel()
                                                            ->required(fn (callable $get) => $get('usage_limit_enabled') === true)
                                                    ])
                                                    ->hidden(fn (callable $get) => $get('usage_limit_enabled') !== true),
                                                Forms\Components\Checkbox::make('usage_limit_per_customer')
                                                    ->label('Limit to one use per customer')
                                                    ->inline(),
                                            ])
                                            ->columnSpan(2),
                                    ])
                            ])
                            ->columnSpan(2),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Summary')
                                    ->helperText('No information saved yet.')
                                    ->hidden(fn ($livewire) => $livewire instanceof EditRecord),
                                Forms\Components\Placeholder::make('Summary')
                                    ->helperText(fn ($record) => "This record was last modified {$record->updated_at->diffForHumans()}.")
                                    ->hidden(fn ($livewire) => $livewire instanceof CreateRecord),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->getStateUsing(fn ($record) => str_replace('_', ' ', ucfirst($record->type)))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Start Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label('End Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_visible')
                    ->label('Approved')
                    ->trueIcon('heroicon-o-badge-check')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }
}

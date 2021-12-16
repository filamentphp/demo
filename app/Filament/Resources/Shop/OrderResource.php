<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\OrderResource\Pages;
use App\Filament\Resources\Shop\OrderResource\RelationManagers;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Squire\Models\Currency;

final class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $slug = 'shop/orders';

    protected static ?string $recordTitleAttribute = 'number';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Card::make()
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('number')
                                                    ->default('OR-' . random_int(100000, 999999))
                                                    ->disabled()
                                                    ->required(),
                                                Forms\Components\BelongsToSelect::make('shop_customer_id')
                                                    ->relationship('customer', 'name')
                                                    ->searchable()
                                                    ->getSearchResultsUsing(fn (string $query) => Customer::where('name', 'like', "%{$query}%")->pluck('name', 'id'))
                                                    ->getOptionLabelUsing(fn ($value): ?string => Customer::find($value)?->name)
                                                    ->required(),
                                                Forms\Components\Select::make('status')
                                                    ->searchable()
                                                    ->options([
                                                        'new' => 'New',
                                                        'processing' => 'Processing',
                                                        'shipped' => 'Shipped',
                                                        'delivered' => 'Delivered',
                                                        'cancelled' => 'Cancelled',
                                                    ])
                                                    ->required(),
                                                Forms\Components\Select::make('currency')
                                                    ->searchable()
                                                    ->getSearchResultsUsing(fn (string $query) => Currency::where('name', 'like', "%{$query}%")->pluck('name', 'id'))
                                                    ->getOptionLabelUsing(fn ($value): ?string => Currency::find($value)?->name)
                                                    ->required(),
                                                Forms\Components\MarkdownEditor::make('notes')
                                                    ->columnSpan(2),
                                            ]),
                                    ]),
                                Forms\Components\Card::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('Items'),
                                        Forms\Components\HasManyRepeater::make('items')
                                            ->relationship('items')
                                            ->schema([
                                                Forms\Components\Grid::make(8)
                                                    ->schema([
                                                        Forms\Components\Select::make('shop_product_id')
                                                            ->label('Product')
                                                            ->options(Product::query()->pluck('name', 'id'))
                                                            ->required()
                                                            ->reactive()
                                                            ->afterStateUpdated(fn ($state, callable $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                                                            ->columnSpan(5),
                                                        Forms\Components\TextInput::make('qty')
                                                            ->numeric()
                                                            ->mask(
                                                                fn (Forms\Components\TextInput\Mask $mask) => $mask
                                                                    ->numeric()
                                                                    ->integer()
                                                            )
                                                            ->default(1)
                                                            ->required(),
                                                        Forms\Components\TextInput::make('unit_price')
                                                            ->label('Unit Price')
                                                            ->disabled()
                                                            ->numeric()
                                                            ->required()
                                                            ->columnSpan(2),
                                                    ])
                                            ])
                                            ->dehydrated()
                                            ->disableLabel()
                                            ->required(),
                                    ]),
                            ])->columnSpan(2),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Card::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('Summary')
                                            ->helperText('No information entered yet.'),
                                    ]),
                            ])->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary',
                        'danger' => 'canceled',
                        'warning' => 'processing',
                        'success' => 'delivered',
                    ]),
                Tables\Columns\TextColumn::make('currency')
                    ->getStateUsing(fn ($record): ?string => Currency::find($record->currency)?->name ?? null)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipping_price')
                    ->label('Shipping cost')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->date(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['number', 'customer.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Customer' => $record->customer->name,
        ];
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['customer', 'items']);
    }
}

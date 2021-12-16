<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\ProductResource\Pages;
use App\Models\Shop\Product;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryMultipleFileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $slug = 'shop/products';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationIcon = 'heroicon-o-lightning-bolt';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                        Forms\Components\TextInput::make('slug')
                                            ->disabled()
                                            ->required()
                                            ->unique(Product::class, 'slug', fn ($record) => $record),
                                        Forms\Components\MarkdownEditor::make('description')
                                            ->columnSpan(2),
                                    ]),
                            ]),
                        Forms\Components\Card::make()
                            ->schema([
                                SpatieMediaLibraryMultipleFileUpload::make('media')
                                    ->collection('product-images')
                                    ->required(),
                            ]),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Pricing'),
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('price')
                                            ->numeric()
                                            ->required(),
                                        Forms\Components\TextInput::make('old_price')
                                            ->label('Compare at price')
                                            ->numeric()
                                            ->required(),
                                        Forms\Components\TextInput::make('cost')
                                            ->label('Cost per item')
                                            ->helperText('Customers won\'t see this price.')
                                            ->numeric()
                                            ->required(),
                                    ]),
                            ]),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Inventory'),
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('sku')
                                            ->label('SKU (Stock Keeping Unit)')
                                            ->required(),
                                        Forms\Components\TextInput::make('barcode')
                                            ->label('Barcode (ISBN, UPC, GTIN, etc.)')
                                            ->required(),
                                        Forms\Components\TextInput::make('qty')
                                            ->label('Quantity')
                                            ->required(),
                                        Forms\Components\TextInput::make('security_stock')
                                            ->helperText('The safety stock is the limit stock for your products which alerts you if the product stock will soon be out of stock.')
                                            ->numeric()
                                            ->required(),
                                    ]),
                            ]),

                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Shipping'),
                                Forms\Components\Checkbox::make('backorder')
                                    ->label('This product can be returned'),
                                Forms\Components\Checkbox::make('requires_shipping')
                                    ->label('This product will be shipped'),
                            ]),
                    ])->columnSpan(2),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Status'),
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Toggle::make('is_visible')
                                            ->label('Visible')
                                            ->helperText('This product will be hidden from all sales channels.')
                                            ->default(true),
                                    ]),
                                Forms\Components\DatePicker::make('published_at')
                                    ->label('Availability')
                                    ->default(now())
                                    ->required(),
                            ]),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Associations'),
                                Forms\Components\BelongsToSelect::make('shop_brand_id')
                                    ->relationship('brand', 'name')
                                    ->searchable()
                                    ->required(),
                                Forms\Components\BelongsToManyMultiSelect::make('categories')
                                    ->relationship('categories', 'name')
                                    ->required(),
                            ]),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('security_stock')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_visible')
                    ->label('Visibility')
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publish Date')
                    ->date()
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'sku', 'brand.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Brand' => $record->brand->name,
        ];
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['brand']);
    }
}

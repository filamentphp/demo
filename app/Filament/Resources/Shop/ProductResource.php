<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\ProductResource\Widgets\ProductStats;
use App\Filament\Resources\Shop\BrandResource\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\Shop\ProductResource\Pages;
use App\Models\Shop\Product;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Component;

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
            ->schema(static::getFormSchema(Forms\Components\Card::class))
            ->columns([
                'sm' => 3,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
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

    public static function getWidgets(): array
    {
        return [
            ProductStats::class,
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
            'Brand' => optional($record->brand)->name,
        ];
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['brand']);
    }

    public static function getFormSchema(string $layout = Forms\Components\Grid::class): array
    {
        return [
            Forms\Components\Group::make()
                ->schema([
                    $layout::make()
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
                                ->columnSpan([
                                    'sm' => 2,
                                ]),
                        ])->columns([
                            'sm' => 2,
                        ]),
                    $layout::make()
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('media')
                                ->collection('product-images')
                                ->multiple()
                                ->minFiles(2)
                                ->maxFiles(5),
                        ])
                        ->columns(1),
                    $layout::make()
                        ->schema([
                            Forms\Components\Placeholder::make('Pricing'),
                            Forms\Components\Grid::make()
                                ->schema([
                                    Forms\Components\TextInput::make('price')
                                        ->numeric()
                                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                        ->required(),
                                    Forms\Components\TextInput::make('old_price')
                                        ->label('Compare at price')
                                        ->numeric()
                                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                        ->required(),
                                    Forms\Components\TextInput::make('cost')
                                        ->label('Cost per item')
                                        ->helperText('Customers won\'t see this price.')
                                        ->numeric()
                                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                        ->required(),
                                ]),
                        ]),
                    $layout::make()
                        ->schema([
                            Forms\Components\Placeholder::make('Inventory'),
                            Forms\Components\Grid::make()
                                ->schema([
                                    Forms\Components\TextInput::make('sku')
                                        ->label('SKU (Stock Keeping Unit)')
                                        ->unique(Product::class, 'sku', fn ($record) => $record)
                                        ->required(),
                                    Forms\Components\TextInput::make('barcode')
                                        ->label('Barcode (ISBN, UPC, GTIN, etc.)')
                                        ->unique(Product::class, 'barcode', fn ($record) => $record)
                                        ->required(),
                                    Forms\Components\TextInput::make('qty')
                                        ->label('Quantity')
                                        ->numeric()
                                        ->rules(['integer', 'min:0'])
                                        ->required(),
                                    Forms\Components\TextInput::make('security_stock')
                                        ->helperText('The safety stock is the limit stock for your products which alerts you if the product stock will soon be out of stock.')
                                        ->numeric()
                                        ->rules(['integer', 'min:0'])
                                        ->required(),
                                ]),
                        ]),

                    $layout::make()
                        ->schema([
                            Forms\Components\Placeholder::make('Shipping'),
                            Forms\Components\Checkbox::make('backorder')
                                ->label('This product can be returned'),
                            Forms\Components\Checkbox::make('requires_shipping')
                                ->label('This product will be shipped'),
                        ])
                        ->columns(1),
                ])->columnSpan([
                    'sm' => 2,
                ]),
            Forms\Components\Group::make()
                ->schema([
                    $layout::make()
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
                        ])
                        ->columns(1),
                    $layout::make()
                        ->schema([
                            Forms\Components\Placeholder::make('Associations'),
                            Forms\Components\BelongsToSelect::make('shop_brand_id')
                                ->relationship('brand', 'name')
                                ->searchable()
                                ->default(fn (Component $livewire) => $livewire instanceof ProductsRelationManager ? $livewire->ownerRecord->id : null)
                                ->disabled(fn (Component $livewire): bool => $livewire instanceof ProductsRelationManager)
                                ->required(),
                            Forms\Components\BelongsToManyMultiSelect::make('categories')
                                ->relationship('categories', 'name')
                                ->required(),
                        ])
                        ->columns(1),
                ])
                ->columnSpan(1),
        ];
    }

    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\SpatieMediaLibraryImageColumn::make('product-image')
                ->label('Image')
                ->collection('product-images'),

            Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('brand.name')
                ->searchable()
                ->sortable()
                ->toggleable(),
            Tables\Columns\TextColumn::make('price')
                ->label('Price')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('sku')
                ->searchable()
                ->sortable()
                ->toggleable(),
            Tables\Columns\TextColumn::make('qty')
                ->searchable()
                ->sortable()
                ->toggleable(),
            Tables\Columns\TextColumn::make('security_stock')
                ->searchable()
                ->sortable()
                ->toggleable()
                ->toggledHiddenByDefault(),
            Tables\Columns\BooleanColumn::make('is_visible')
                ->label('Visibility')
                ->sortable()
                ->toggleable(),
            Tables\Columns\TextColumn::make('published_at')
                ->label('Publish Date')
                ->date()
                ->sortable()
                ->toggleable()
                ->toggledHiddenByDefault(),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return self::$model::whereColumn('qty', '<', 'security_stock')->count();
    }
}

<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\ReviewResource\Pages;
use App\Models\Shop\Review;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $slug = 'shop/reviews';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\BelongsToSelect::make('blog_product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->required(),
                                Forms\Components\BelongsToSelect::make('blog_customer_id')
                                    ->relationship('customer', 'name')
                                    ->searchable()
                                    ->required(),
                                Forms\Components\TextInput::make('title')
                                    ->required(),
                                Forms\Components\TextInput::make('rating')
                                    ->label('Rating (1-5)')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(5)
                                    ->required(),
                                Forms\Components\Toggle::make('is_visible')
                                    ->label('Visible to customers.')
                                    ->default(true)
                                    ->columnSpan(2),
                                Forms\Components\MarkdownEditor::make('content')
                                    ->label('Content')
                                    ->columnSpan(2),
                            ]),
                    ])
                    ->columnSpan(2),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (?Review $record): string => $record ? $record->created_at->diffForHumans() : '-'),
                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (?Review $record): string => $record ? $record->updated_at->diffForHumans() : '-'),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_visible')
                    ->label('Visibility')
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'customer.name', 'product.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Customer' => $record->customer->name,
            'Product' => $record->product->name,
        ];
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['customer', 'product']);
    }
}

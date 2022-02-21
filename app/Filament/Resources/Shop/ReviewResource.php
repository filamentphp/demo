<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\CustomerResource\RelationManagers\ReviewsRelationManager;
use App\Filament\Resources\Shop\ReviewResource\Pages;
use App\Models\Shop\Review;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

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
                    ->schema(static::getFormSchema())
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan([
                        'sm' => 2,
                    ]),
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

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\BelongsToSelect::make('shop_product_id')
                ->relationship('product', 'name')
                ->searchable()
                ->required(),
            Forms\Components\BelongsToSelect::make('shop_customer_id')
                ->relationship('customer', 'name')
                ->searchable()
                ->required()
                ->default(fn (Component $livewire) => $livewire instanceof ReviewsRelationManager ? $livewire->ownerRecord->id : null)
                ->disabled(fn (Component $livewire): bool => $livewire instanceof ReviewsRelationManager),
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
                ->columnSpan([
                    'sm' => 2,
                ]),
            Forms\Components\MarkdownEditor::make('content')
                ->label('Content')
                ->columnSpan([
                    'sm' => 2,
                ]),
        ];
    }

    public static function getTableColumns(): array
    {
        return [
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
        ];
    }
}

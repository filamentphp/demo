<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\CategoryResource\Pages;
use App\Filament\Resources\Shop\CategoryResource\RelationManagers;
use App\Models\Shop\Category;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $slug = 'shop/categories';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 3;

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
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                        Forms\Components\TextInput::make('slug')
                                            ->disabled()
                                            ->required()
                                            ->unique(Category::class, 'slug', fn ($record) => $record),
                                    ]),
                                Forms\Components\BelongsToSelect::make('parent_id')
                                    ->label('Parent')
                                    ->relationship('parent', 'name', fn (Builder $query) => $query->where('parent_id', null))
                                    ->searchable()
                                    ->placeholder('Select parent category'),
                                Forms\Components\Placeholder::make('Visibility'),
                                Forms\Components\Toggle::make('is_visible')
                                    ->label('Set category visibility for the customers.')
                                    ->default(true)
                                    ->inline(),
                                Forms\Components\MarkdownEditor::make('description')
                                    ->label('Description'),
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_visible')
                    ->label('Visibility')
                    ->trueIcon('heroicon-o-badge-check')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated Date')
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}

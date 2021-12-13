<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blog;

use App\Filament\Resources\Blog\CategoryResource\Pages;
use App\Filament\Resources\Blog\CategoryResource\RelationManagers;
use App\Models\Blog\Category;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;

final class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $slug = 'blog/categories';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

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
                                        Forms\Components\MarkdownEditor::make('description')
                                            ->columnSpan(2),
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Placeholder::make('Visibility'),
                                                Forms\Components\Toggle::make('is_visible')
                                                    ->label('Setup category visibility for the users.')
                                                    ->default(true),
                                            ]),
                                    ]),
                            ])
                            ->columnSpan(2),
                        Forms\Components\Card::make()
                            ->schema([])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_visible')
                    ->label('Visibility')
                    ->trueIcon('heroicon-o-badge-check')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}

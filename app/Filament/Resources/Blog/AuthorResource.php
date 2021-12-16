<?php

namespace App\Filament\Resources\Blog;

use App\Filament\Resources\Blog\AuthorResource\Pages;
use App\Filament\Resources\Blog\AuthorResource\RelationManagers;
use App\Models\Blog\Author;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static ?string $slug = 'blog/authors';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 2;

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
                                            ->required(),
                                        Forms\Components\TextInput::make('email')
                                            ->required()
                                            ->email()
                                            ->unique(Author::class, 'email', fn ($record) => $record),
                                        Forms\Components\MarkdownEditor::make('bio')
                                            ->columnSpan(2),
                                        Forms\Components\TextInput::make('github_handle')
                                            ->label('GitHub')
                                            ->url(),
                                        Forms\Components\TextInput::make('twitter_handle')
                                            ->label('Twitter')
                                            ->url(),
                                    ]),
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('github_handle')
                    ->label('GitHub'),
                Tables\Columns\TextColumn::make('twitter_handle')
                    ->label('Twitter'),
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
            'index' => Pages\ListAuthors::route('/'),
            'create' => Pages\CreateAuthor::route('/create'),
            'edit' => Pages\EditAuthor::route('/{record}/edit'),
        ];
    }
}

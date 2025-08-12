<?php

namespace App\Filament\Resources\Blog\Categories\Schemas;

use App\Models\Blog\Category;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->maxLength(255)
                    ->unique(Category::class, 'slug', ignoreRecord: true),

                RichEditor::make('description')
                    ->columnSpan('full'),

                Toggle::make('is_visible')
                    ->label('Visibility')
                    ->default(true),
            ]);
    }
}

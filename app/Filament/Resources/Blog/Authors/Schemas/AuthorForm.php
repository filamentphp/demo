<?php

namespace App\Filament\Resources\Blog\Authors\Schemas;

use App\Models\Blog\Author;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AuthorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email address')
                    ->required()
                    ->maxLength(255)
                    ->email()
                    ->unique(Author::class, 'email', ignoreRecord: true),

                RichEditor::make('bio')
                    ->columnSpan('full'),

                TextInput::make('github_handle')
                    ->label('GitHub handle')
                    ->maxLength(255),

                TextInput::make('twitter_handle')
                    ->maxLength(255),
            ]);
    }
}

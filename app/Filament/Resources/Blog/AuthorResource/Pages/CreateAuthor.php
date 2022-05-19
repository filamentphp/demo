<?php

namespace App\Filament\Resources\Blog\AuthorResource\Pages;

use App\Filament\Resources\Blog\AuthorResource;
use App\Models\Blog\Author;
use Filament\Forms;
use Filament\Resources\Pages\CreateRecord;

class CreateAuthor extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = AuthorResource::class;

    protected function getSteps(): array
    {
        return [
            Forms\Components\Wizard\Step::make('Details')
                ->description('Your personal info')
                ->icon('heroicon-o-user')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required(),
                    Forms\Components\TextInput::make('email')
                        ->required()
                        ->email()
                        ->unique(Author::class, 'email', fn ($record) => $record),
                ]),
            Forms\Components\Wizard\Step::make('Bio')
                ->description('Tell us about you')
                ->icon('heroicon-o-pencil')
                ->schema([
                    Forms\Components\MarkdownEditor::make('bio')
                        ->disableLabel()
                        ->columnSpan([
                            'sm' => 2,
                        ]),
                ]),
            Forms\Components\Wizard\Step::make('Social')
                ->description('Where can we find you?')
                ->icon('heroicon-o-chat')
                ->schema([
                    Forms\Components\TextInput::make('github_handle')
                        ->label('GitHub'),
                    Forms\Components\TextInput::make('twitter_handle')
                        ->label('Twitter'),
                ]),
        ];
    }
}

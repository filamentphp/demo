<?php

namespace App\Filament\Resources\Blog\Authors\Tables;

use App\Models\Blog\Author;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuthorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make('name')
                            ->searchable()
                            ->sortable()
                            ->weight(FontWeight::Medium)
                            ->alignLeft(),

                        TextColumn::make('email')
                            ->label('Email address')
                            ->searchable()
                            ->sortable()
                            ->color('gray')
                            ->alignLeft(),
                    ])->space(),

                    Stack::make([
                        TextColumn::make('github_handle')
                            ->icon('icon-github')
                            ->label('GitHub handle')
                            ->alignLeft(),

                        TextColumn::make('twitter_handle')
                            ->icon('icon-twitter')
                            ->alignLeft(),
                    ])->space(2),
                ])->from('md'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('view_github')
                        ->label('View GitHub')
                        ->icon('icon-github')
                        ->color('gray')
                        ->url(fn (Author $record): string => "https://github.com/{$record->github_handle}")
                        ->openUrlInNewTab()
                        ->hidden(fn (Author $record): bool => blank($record->github_handle)),
                    Action::make('view_twitter')
                        ->label('View Twitter')
                        ->icon('icon-twitter')
                        ->color('gray')
                        ->url(fn (Author $record): string => "https://x.com/{$record->twitter_handle}")
                        ->openUrlInNewTab()
                        ->hidden(fn (Author $record): bool => blank($record->twitter_handle)),
                    EditAction::make(),
                    DeleteAction::make()
                        ->action(function (): void {
                            Notification::make()
                                ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                                ->warning()
                                ->send();
                        }),
                ]),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->action(function (): void {
                        Notification::make()
                            ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                            ->warning()
                            ->send();
                    }),
            ]);
    }
}

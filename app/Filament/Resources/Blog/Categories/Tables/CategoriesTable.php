<?php

namespace App\Filament\Resources\Blog\Categories\Tables;

use App\Models\Blog\PostCategory;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_visible')
                    ->label('Visibility'),
                TextColumn::make('updated_at')
                    ->label('Last modified at')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('toggle_visibility')
                        ->icon(fn (PostCategory $record): Heroicon => $record->is_visible ? Heroicon::EyeSlash : Heroicon::Eye)
                        ->color('gray')
                        ->label(fn (PostCategory $record): string => $record->is_visible ? 'Hide category' : 'Show category')
                        ->action(fn (PostCategory $record) => $record->update(['is_visible' => ! $record->is_visible])),
                    ViewAction::make(),
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

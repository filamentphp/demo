<?php

namespace App\Filament\Resources\Blog\Links\Tables;

use App\Models\Blog\Link;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    SpatieMediaLibraryImageColumn::make('image')
                        ->collection('link-images')
                        ->conversion('thumb')
                        ->imageHeight('100%')
                        ->imageWidth('100%'),
                    Stack::make([
                        TextColumn::make('title')
                            ->weight(FontWeight::Bold),
                        TextColumn::make('url')
                            ->formatStateUsing(fn (string $state): string => str($state)->after('://')->ltrim('www.')->trim('/'))
                            ->color('gray')
                            ->lineClamp(1),
                    ]),
                ])->space(3),
                Panel::make([
                    Split::make([
                        ColorColumn::make('color')
                            ->grow(false),
                        TextColumn::make('description')
                            ->color('gray'),
                    ]),
                ])->collapsible(),
            ])
            ->filters([
                //
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->paginated([
                18,
                36,
                72,
                'all',
            ])
            ->recordActions([
                Action::make('visit')
                    ->label('Visit link')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn (Link $record): string => '#' . urlencode($record->url)),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function (): void {
                            Notification::make()
                                ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                                ->warning()
                                ->send();
                        }),
                ]),
            ]);
    }
}

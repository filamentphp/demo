<?php

namespace App\Filament\Resources\Blog;

use App\Filament\Resources\Blog\LinkResource\Pages\CreateLink;
use App\Filament\Resources\Blog\LinkResource\Pages\EditLink;
use App\Filament\Resources\Blog\LinkResource\Pages\ListLinks;
use App\Filament\Resources\Blog\LinkResource\Pages\ViewLink;
use App\Models\Blog\Link;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ColorEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use UnitEnum;

class LinkResource extends Resource
{
    use Translatable;

    protected static ?string $model = Link::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-link';

    protected static string | UnitEnum | null $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->maxLength(255)
                    ->required(),
                ColorPicker::make('color')
                    ->required()
                    ->hex()
                    ->hexColor(),
                Textarea::make('description')
                    ->maxLength(1024)
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('url')
                    ->label('URL')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                ColorEntry::make('color'),
                TextEntry::make('description')
                    ->columnSpanFull(),
                TextEntry::make('url')
                    ->label('URL')
                    ->columnSpanFull()
                    ->url(fn (Link $record): string => '#' . urlencode($record->url)),
                ImageEntry::make('image'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    ImageColumn::make('image')
                        ->imageHeight('100%')
                        ->width('100%'),
                    Stack::make([
                        TextColumn::make('title')
                            ->weight(FontWeight::Bold),
                        TextColumn::make('url')
                            ->formatStateUsing(fn (string $state): string => str($state)->after('://')->ltrim('www.')->trim('/'))
                            ->color('gray')
                            ->limit(30),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLinks::route('/'),
            'create' => CreateLink::route('/create'),
            'view' => ViewLink::route('/{record}'),
            'edit' => EditLink::route('/{record}/edit'),
        ];
    }
}

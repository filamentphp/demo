<?php

namespace App\Filament\Resources\Blog\Posts\Schemas;

use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Flex::make([
                            Grid::make(2)
                                ->schema([
                                    Group::make([
                                        TextEntry::make('title'),
                                        TextEntry::make('slug'),
                                        TextEntry::make('published_at')
                                            ->label('Publishing date')
                                            ->badge()
                                            ->date()
                                            ->color('success'),
                                    ]),
                                    Group::make([
                                        TextEntry::make('author.name'),
                                        TextEntry::make('category.name'),
                                        SpatieTagsEntry::make('tags'),
                                    ]),
                                ]),
                            SpatieMediaLibraryImageEntry::make('image')
                                ->collection('post-images')
                                ->hiddenLabel()
                                ->grow(false),
                        ])->from('lg'),
                    ]),
                Section::make('Content')
                    ->schema([
                        TextEntry::make('content')
                            ->prose()
                            ->markdown()
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),
            ]);
    }
}

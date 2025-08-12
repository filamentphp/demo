<?php

namespace App\Filament\Resources\Blog\Links\Schemas;

use App\Models\Blog\Link;
use Filament\Infolists\Components\ColorEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LinkInfolist
{
    public static function configure(Schema $schema): Schema
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
                SpatieMediaLibraryImageEntry::make('image')
                    ->collection('link-images')
                    ->conversion('thumb'),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Blog\Links\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LinkForm
{
    public static function configure(Schema $schema): Schema
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
                SpatieMediaLibraryFileUpload::make('image')
                    ->collection('link-images')
                    ->acceptedFileTypes(['image/jpeg']),
            ]);
    }
}

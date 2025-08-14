<?php

namespace App\Filament\Resources\Blog\Links;

use App\Filament\Resources\Blog\Links\Pages\CreateLink;
use App\Filament\Resources\Blog\Links\Pages\EditLink;
use App\Filament\Resources\Blog\Links\Pages\ListLinks;
use App\Filament\Resources\Blog\Links\Pages\ViewLink;
use App\Filament\Resources\Blog\Links\Schemas\LinkForm;
use App\Filament\Resources\Blog\Links\Schemas\LinkInfolist;
use App\Filament\Resources\Blog\Links\Tables\LinksTable;
use App\Models\Blog\Link;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
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
        return LinkForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LinkInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LinksTable::configure($table);
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

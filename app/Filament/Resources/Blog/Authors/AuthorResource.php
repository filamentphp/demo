<?php

namespace App\Filament\Resources\Blog\Authors;

use App\Filament\DemoIconAlias;
use App\Filament\Resources\Blog\Authors\Pages\ManageAuthors;
use App\Filament\Resources\Blog\Authors\Schemas\AuthorForm;
use App\Filament\Resources\Blog\Authors\Tables\AuthorsTable;
use App\Models\Blog\Author;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

/**
 * @extends \Filament\Resources\Resource<Author>
 */
class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static ?string $slug = 'blog/authors';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 2;

    public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return FilamentIcon::resolve(DemoIconAlias::RESOURCES_BLOG_AUTHORS_NAVIGATION) ?? Heroicon::OutlinedUsers;
    }

    public static function form(Schema $schema): Schema
    {
        return AuthorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuthorsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAuthors::route('/'),
        ];
    }
}

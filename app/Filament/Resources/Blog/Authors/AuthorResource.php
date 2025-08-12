<?php

namespace App\Filament\Resources\Blog\Authors;

use App\Filament\Resources\Blog\Authors\Pages\ManageAuthors;
use App\Filament\Resources\Blog\Authors\Schemas\AuthorForm;
use App\Filament\Resources\Blog\Authors\Tables\AuthorsTable;
use App\Models\Blog\Author;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static ?string $slug = 'blog/authors';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup = 'Blog';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 2;

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

<?php

namespace App\Filament\Resources\Blog\Categories;

use App\Filament\Resources\Blog\Categories\Pages\ManageCategories;
use App\Filament\Resources\Blog\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Blog\Categories\Schemas\CategoryInfolist;
use App\Filament\Resources\Blog\Categories\Tables\CategoriesTable;
use App\Models\Blog\PostCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = PostCategory::class;

    protected static ?string $slug = 'blog/categories';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup = 'Blog';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CategoryInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCategories::route('/'),
        ];
    }
}

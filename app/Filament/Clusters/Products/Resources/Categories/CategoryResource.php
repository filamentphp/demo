<?php

namespace App\Filament\Clusters\Products\Resources\Categories;

use App\Filament\Clusters\Products\ProductsCluster;
use App\Filament\Clusters\Products\Resources\Categories\Pages\CreateCategory;
use App\Filament\Clusters\Products\Resources\Categories\Pages\EditCategory;
use App\Filament\Clusters\Products\Resources\Categories\Pages\ListCategories;
use App\Filament\Clusters\Products\Resources\Categories\RelationManagers\ProductsRelationManager;
use App\Filament\Clusters\Products\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Clusters\Products\Resources\Categories\Tables\CategoriesTable;
use App\Models\Shop\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $cluster = ProductsCluster::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationParentItem = 'Products';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}

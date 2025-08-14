<?php

namespace App\Filament\Clusters\Products\Resources\Brands;

use App\Filament\Clusters\Products\ProductsCluster;
use App\Filament\Clusters\Products\Resources\Brands\Pages\CreateBrand;
use App\Filament\Clusters\Products\Resources\Brands\Pages\EditBrand;
use App\Filament\Clusters\Products\Resources\Brands\Pages\ListBrands;
use App\Filament\Clusters\Products\Resources\Brands\RelationManagers\AddressesRelationManager;
use App\Filament\Clusters\Products\Resources\Brands\RelationManagers\ProductsRelationManager;
use App\Filament\Clusters\Products\Resources\Brands\Schemas\BrandForm;
use App\Filament\Clusters\Products\Resources\Brands\Tables\BrandsTable;
use App\Models\Shop\Brand;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $cluster = ProductsCluster::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-bookmark-square';

    protected static ?string $navigationParentItem = 'Products';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return BrandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrandsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::class,
            AddressesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBrands::route('/'),
            'create' => CreateBrand::route('/create'),
            'edit' => EditBrand::route('/{record}/edit'),
        ];
    }
}

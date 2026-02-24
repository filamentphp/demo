<?php

namespace App\Filament\Resources\Shop\Brands;

use App\Filament\Resources\Shop\Brands\Pages\CreateBrand;
use App\Filament\Resources\Shop\Brands\Pages\EditBrand;
use App\Filament\Resources\Shop\Brands\Pages\ListBrands;
use App\Filament\Resources\Shop\Brands\RelationManagers\AddressesRelationManager;
use App\Filament\Resources\Shop\Brands\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\Shop\Brands\Schemas\BrandForm;
use App\Filament\Resources\Shop\Brands\Tables\BrandsTable;
use App\Models\Shop\Brand;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedBookmarkSquare;

    protected static string | UnitEnum | null $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'shop/brands';

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

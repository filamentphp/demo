<?php

namespace App\Filament\Resources\Shop\BrandResource\RelationManagers;

use App\Filament\Resources\Shop\ProductResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Filament\Resources\Table;

class ProductsRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(ProductResource::getFormSchema())
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(ProductResource::getTableColumns())
            ->filters([
                //
            ]);
    }
}

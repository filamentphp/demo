<?php

namespace App\Filament\Resources\Shop\CategoryResource\RelationManagers;

use App\Filament\Resources\Shop\ProductResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\BelongsToManyRelationManager;
use Filament\Resources\Table;

class ProductsRelationManager extends BelongsToManyRelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(ProductResource::getFormSchema())
            ->columns([
                'sm' => 3,
                'lg' => null,
            ]);
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

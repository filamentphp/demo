<?php

namespace App\Filament\Clusters\Products\Resources\CategoryResource\RelationManagers;

use App\Filament\Clusters\Products\Resources\ProductResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return ProductResource::form($form);
    }

    public function table(Table $table): Table
    {
        return ProductResource::table($table)
            ->headerActions([
                \Filament\Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                \Filament\Tables\Actions\DeleteAction::make(),
            ])
            ->groupedBulkActions([
                \Filament\Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}

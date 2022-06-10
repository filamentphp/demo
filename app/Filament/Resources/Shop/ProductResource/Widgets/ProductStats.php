<?php

namespace App\Filament\Resources\Shop\ProductResource\Widgets;

use App\Models\Shop\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ProductStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Products', Product::count()),
            Card::make('Product Inventory', Product::sum('qty')),
            Card::make('Average price', number_format(Product::avg('price'), 2)),
        ];
    }
}

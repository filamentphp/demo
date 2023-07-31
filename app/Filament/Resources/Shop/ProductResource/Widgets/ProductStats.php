<?php

namespace App\Filament\Resources\Shop\ProductResource\Widgets;

use App\Filament\Resources\Shop\ProductResource\Pages\ListProducts;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ProductStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListProducts::class;
    }

    protected function getCards(): array
    {
        return [
            Card::make('Total Products', $this->getPageTableQuery()->count()),
            Card::make('Product Inventory', $this->getPageTableQuery()->sum('qty')),
            Card::make('Average price', number_format($this->getPageTableQuery()->avg('price'), 2)),
        ];
    }
}

<?php

namespace App\Filament\Clusters\Products\Resources\Products\Widgets;

use App\Filament\Clusters\Products\Resources\Products\Pages\ListProducts;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListProducts::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', $this->getPageTableQuery()->count()),
            Stat::make('Product Inventory', $this->getPageTableQuery()->sum('qty')),
            Stat::make('Average price', number_format((float) $this->getPageTableQuery()->avg('price'), 2)),
        ];
    }
}

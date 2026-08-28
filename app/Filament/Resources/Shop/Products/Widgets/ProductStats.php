<?php

namespace App\Filament\Resources\Shop\Products\Widgets;

use App\Filament\Resources\Shop\Products\Pages\ListProducts;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

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
            Stat::make('Total products', $this->getPageTableQuery()->count()),
            Stat::make('Product inventory', $this->getPageTableQuery()->sum('qty')),
            Stat::make('Average price', Number::currency((float) $this->getPageTableQuery()->avg('price'), in: 'USD')),
        ];
    }
}

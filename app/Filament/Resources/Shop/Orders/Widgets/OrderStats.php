<?php

namespace App\Filament\Resources\Shop\Orders\Widgets;

use App\Filament\Resources\Shop\Orders\Pages\ListOrders;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

    protected function getStats(): array
    {
        $end = now();
        $start = $end->copy()->subYear();
        $tableQuery = $this->getPageTableQuery();

        $orderData = Trend::query((clone $tableQuery)->reorder())
            ->between(
                start: $start,
                end: $end,
            )
            ->perMonth()
            ->count();
        $openOrderData = Trend::query(
            (clone $tableQuery)
                ->reorder()
                ->whereIn('status', ['new', 'processing'])
        )
            ->between(start: $start, end: $end)
            ->perMonth()
            ->count();
        $averagePriceData = Trend::query((clone $tableQuery)->reorder())
            ->between(start: $start, end: $end)
            ->perMonth()
            ->average('total_price');

        return [
            Stat::make('Orders', (clone $tableQuery)->count())
                ->chart(
                    $orderData
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
            Stat::make('Open orders', (clone $tableQuery)->whereIn('status', ['new', 'processing'])->count())
                ->chart(
                    $openOrderData
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
            Stat::make('Average order value', Number::currency((float) (clone $tableQuery)->avg('total_price'), in: 'USD'))
                ->chart(
                    $averagePriceData
                        ->map(fn (TrendValue $value) => (float) $value->aggregate)
                        ->toArray()
                ),
        ];
    }
}

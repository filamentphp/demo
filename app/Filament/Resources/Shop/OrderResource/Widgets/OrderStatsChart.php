<?php

namespace App\Filament\Resources\Shop\OrderResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Shop\Order;
use Filament\Widgets\PieChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrderStatsChart extends PieChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = "10s";

    protected static ?int $sort = 1;

    protected static ?string $heading = 'Current year orders per month';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '500px';

    protected static ?array $options = [
        'scales' => [
            'x' => [
                'display' => false,
            ],
            'y' => [
                'display' => false,
            ],
        ]
    ];


    protected function getType(): string
    {
        return 'pie';
    }

    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

     protected function getData(): array
     {
        $orderData = Trend::model(Order::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        $data = $orderData->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray();

         return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data,
                    'backgroundColor' => [
                        "rgb(6,150,104)",
                        "rgb(155,226,211)",
                        "rgb(32,80,46)",
                        "rgb(162,230,124)",
                        "rgb(112,137,131)",
                        "rgb(27,228,109)",
                        "rgb(25,71,125)",
                        "rgb(204,171,240)",
                        "rgb(108,44,118)",
                        "rgb(162,97,246)",
                        "rgb(56,181,252)",
                        "rgb(25,50,191)"
                    ],
                    'hoverOffset' => '10',
                ],
            ],
        ];
     }
}

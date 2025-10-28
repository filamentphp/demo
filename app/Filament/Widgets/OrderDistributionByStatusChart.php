<?php

namespace App\Filament\Widgets;

use Filament\CustomDashboardsPlugin\Widgets\Concerns\InteractsWithCustomDashboards;
use Filament\CustomDashboardsPlugin\Widgets\Contracts\CustomDashboardWidget;
use Filament\Widgets\ChartWidget;

class OrderDistributionByStatusChart extends ChartWidget
{
    use InteractsWithCustomDashboards;

    protected ?string $heading = 'Order distribution by status';

    protected static ?int $sort = 6;

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => [55, 25, 10, 7, 3],
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(201, 203, 207)',
                    ],
                ],
            ],
            'labels' => ['Completed', 'Processing', 'Pending', 'Cancelled', 'Refunded'],
        ];
    }

    public static function getCustomDashboardLabel(): string
    {
        return 'Order distribution by status chart';
    }

    public static function getCustomDashboardDescription(): ?string
    {
        return 'A pie chart showing the distribution of orders by status.';
    }
}

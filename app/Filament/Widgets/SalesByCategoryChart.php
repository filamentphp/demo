<?php

namespace App\Filament\Widgets;

use Filament\CustomDashboardsPlugin\Widgets\Concerns\InteractsWithCustomDashboards;
use Filament\CustomDashboardsPlugin\Widgets\Contracts\CustomDashboardWidget;
use Filament\Widgets\ChartWidget;

class SalesByCategoryChart extends ChartWidget
{
    use InteractsWithCustomDashboards;

    protected ?string $heading = 'Sales by category';

    protected static ?int $sort = 3;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => '2025',
                    'data' => [120, 190, 30, 50, 20, 30],
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(201, 203, 207)',
                        'rgb(153, 102, 255)',
                    ],

                ],
                [
                    'label' => '2024',
                    'data' => [100, 150, 40, 60, 30, 45],
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(201, 203, 207)',
                        'rgb(153, 102, 255)',
                    ],
                ],
            ],
            'labels' => ['Electronics', 'Clothing', 'Books', 'Home', 'Beauty', 'Toys'],
        ];
    }

    public static function getCustomDashboardLabel(): string
    {
        return 'Sales by category chart';
    }

    public static function getCustomDashboardDescription(): ?string
    {
        return 'A bar chart comparing values across categories.';
    }
}

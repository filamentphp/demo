<?php

namespace App\Filament\Widgets;

use Filament\CustomDashboardsPlugin\Widgets\Concerns\InteractsWithCustomDashboards;
use Filament\CustomDashboardsPlugin\Widgets\Contracts\CustomDashboardWidget;
use Filament\Widgets\ChartWidget;

class CustomersChart extends ChartWidget
{
    use InteractsWithCustomDashboards;

    protected ?string $heading = 'Total customers';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Customers',
                    'data' => [4344, 5676, 6798, 7890, 8987, 9388, 10343, 10524, 13664, 14345, 15753, 17332],
                    'fill' => 'start',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    public static function getCustomDashboardLabel(): string
    {
        return 'Customers chart';
    }

    public static function getCustomDashboardDescription(): ?string
    {
        return 'A chart of total customers over time, grouped by month.';
    }
}

<?php

namespace App\Filament\Widgets;

use Filament\CustomDashboardsPlugin\Widgets\Concerns\InteractsWithCustomDashboards;
use Filament\CustomDashboardsPlugin\Widgets\Contracts\CustomDashboardWidget;
use Filament\Widgets\ChartWidget;

class RevenueShareByChannelChart extends ChartWidget
{
    use InteractsWithCustomDashboards;

    protected ?string $heading = 'Revenue share by channel';

    protected static ?int $sort = 5;

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Channels',
                    'data' => [45, 25, 15, 10, 5],
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(201, 203, 207)',
                    ],
                ],
            ],
            'labels' => ['Web', 'Mobile', 'Retail', 'Wholesale', 'Other'],
        ];
    }

    public static function getCustomDashboardLabel(): string
    {
        return 'Revenue share by channel chart';
    }

    public static function getCustomDashboardDescription(): ?string
    {
        return 'A doughnut chart showing the share of revenue by sales channel.';
    }
}

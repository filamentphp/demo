<?php

namespace App\Filament\Widgets;

use App\Models\Shop\Order;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class OrderValueDistributionChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Order Value Distribution';

    protected static ?int $sort = 6;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $startDate = filled($this->pageFilters['startDate'] ?? null)
            ? Carbon::parse($this->pageFilters['startDate'])
            : null;
        $endDate = filled($this->pageFilters['endDate'] ?? null)
            ? Carbon::parse($this->pageFilters['endDate'])
            : null;
        $orderStatuses = $this->pageFilters['orderStatuses'] ?? null;

        $orders = Order::query()
            ->whereNotNull('total_price')
            ->when($startDate, fn ($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->where('created_at', '<=', $endDate))
            ->when(filled($orderStatuses), fn ($q) => $q->whereIn('status', $orderStatuses))
            ->pluck('total_price');

        $ranges = [
            '$0-50' => 0,
            '$50-100' => 0,
            '$100-250' => 0,
            '$250-500' => 0,
            '$500-1k' => 0,
            '$1k+' => 0,
        ];

        foreach ($orders as $price) {
            $price = (float) $price;

            if ($price < 50) {
                $ranges['$0-50']++;
            } elseif ($price < 100) {
                $ranges['$50-100']++;
            } elseif ($price < 250) {
                $ranges['$100-250']++;
            } elseif ($price < 500) {
                $ranges['$250-500']++;
            } elseif ($price < 1000) {
                $ranges['$500-1k']++;
            } else {
                $ranges['$1k+']++;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => array_values($ranges),
                    'backgroundColor' => [
                        '#22c55e',
                        '#3b82f6',
                        '#8b5cf6',
                        '#f59e0b',
                        '#ef4444',
                        '#ec4899',
                    ],
                    'borderColor' => [
                        '#22c55e',
                        '#3b82f6',
                        '#8b5cf6',
                        '#f59e0b',
                        '#ef4444',
                        '#ec4899',
                    ],
                ],
            ],
            'labels' => array_keys($ranges),
        ];
    }
}

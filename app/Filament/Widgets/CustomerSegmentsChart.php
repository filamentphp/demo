<?php

namespace App\Filament\Widgets;

use App\Models\Shop\Customer;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class CustomerSegmentsChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Customer Segments';

    protected static ?int $sort = 5;

    protected function getType(): string
    {
        return 'doughnut';
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

        $customers = Customer::withCount(['orders' => function ($query) use ($startDate, $endDate, $orderStatuses): void {
            $query->when($startDate, fn ($q) => $q->where('created_at', '>=', $startDate))
                ->when($endDate, fn ($q) => $q->where('created_at', '<=', $endDate))
                ->when(filled($orderStatuses), fn ($q) => $q->whereIn('status', $orderStatuses));
        }])->get();

        $segments = [
            'One-time (1)' => 0,
            'Occasional (2-3)' => 0,
            'Regular (4-9)' => 0,
            'VIP (10+)' => 0,
        ];

        foreach ($customers as $customer) {
            $count = $customer->orders_count;

            if ($count <= 0) {
                continue;
            }

            if ($count === 1) {
                $segments['One-time (1)']++;
            } elseif ($count <= 3) {
                $segments['Occasional (2-3)']++;
            } elseif ($count <= 9) {
                $segments['Regular (4-9)']++;
            } else {
                $segments['VIP (10+)']++;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Customers',
                    'data' => array_values($segments),
                    'backgroundColor' => [
                        '#9ca3af',
                        '#3b82f6',
                        '#22c55e',
                        '#f59e0b',
                    ],
                ],
            ],
            'labels' => array_keys($segments),
        ];
    }
}

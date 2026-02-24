<?php

namespace App\Filament\Widgets;

use App\Models\Shop\Customer;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class CustomerGrowthChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Customer Growth';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $startDate = filled($this->pageFilters['startDate'] ?? null)
            ? Carbon::parse($this->pageFilters['startDate'])->startOfMonth()
            : Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = filled($this->pageFilters['endDate'] ?? null)
            ? Carbon::parse($this->pageFilters['endDate'])->endOfMonth()
            : now();

        $months = collect();
        $cursor = $startDate->copy();

        while ($cursor->lte($endDate)) {
            $months->push($cursor->copy());
            $cursor->addMonth();
        }

        $customers = Customer::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->get()
            ->groupBy(fn (Customer $customer): string => $customer->created_at?->format('Y-m') ?? '')
            ->map(fn ($group) => $group->count());

        $labels = [];
        $data = [];

        foreach ($months as $month) {
            $labels[] = $month->format('M Y');
            $data[] = $customers->get($month->format('Y-m'), 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Customers',
                    'data' => $data,
                    'fill' => 'start',
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\Shop\OrderItem;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TopProductsByRevenueChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Top Products by Revenue';

    protected static ?int $sort = 4;

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
        $productCategory = $this->pageFilters['productCategory'] ?? null;

        $products = OrderItem::query()
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->when($startDate, fn ($q) => $q->where('orders.created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->where('orders.created_at', '<=', $endDate))
            ->when(filled($orderStatuses), fn ($q) => $q->whereIn('orders.status', $orderStatuses))
            ->when(filled($productCategory), fn ($q) => $q->whereExists(
                fn ($sub) => $sub->select(DB::raw(1))
                    ->from('product_category_product')
                    ->whereColumn('product_category_product.product_id', 'products.id')
                    ->where('product_category_product.product_category_id', $productCategory)
            ))
            ->select('products.name', DB::raw('SUM(order_items.qty * order_items.unit_price) as revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $products->pluck('revenue')->map(fn ($v) => round((float) $v, 2))->all(),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $products->pluck('name')->map(fn (string $name) => strlen($name) > 25 ? substr($name, 0, 25) . '...' : $name)->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
        ];
    }
}

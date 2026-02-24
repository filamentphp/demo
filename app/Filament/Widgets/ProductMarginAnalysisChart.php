<?php

namespace App\Filament\Widgets;

use App\Models\Shop\Product;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ProductMarginAnalysisChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Product Margin Analysis';

    protected static ?int $sort = 7;

    protected function getType(): string
    {
        return 'scatter';
    }

    protected function getData(): array
    {
        $productCategory = $this->pageFilters['productCategory'] ?? null;

        $products = Product::query()
            ->whereNotNull('price')
            ->where('price', '>', 0)
            ->whereNotNull('cost')
            ->where('cost', '>', 0)
            ->when(filled($productCategory), fn ($q) => $q->whereHas(
                'productCategories',
                fn ($sub) => $sub->where('product_categories.id', $productCategory)
            ))
            ->get(['id', 'price', 'cost', 'qty']);

        $dataPoints = $products->map(fn (Product $product) => [
            'x' => round((((float) $product->price - (float) $product->cost) / (float) $product->price) * 100, 1),
            'y' => (int) $product->qty,
        ])->all();

        return [
            'datasets' => [
                [
                    'label' => 'Products',
                    'showLine' => false,
                    'data' => $dataPoints,
                    'backgroundColor' => '#3b82f6',
                ],
            ],
        ];
    }
}

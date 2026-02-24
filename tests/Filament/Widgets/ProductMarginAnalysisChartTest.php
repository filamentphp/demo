<?php

use App\Filament\Widgets\ProductMarginAnalysisChart;
use App\Models\Shop\Product;
use Livewire\Livewire;

it('renders the product margin analysis widget', function () {
    // Products with both price and cost set (should appear)
    Product::factory()->count(5)->create([
        'price' => fake()->randomFloat(2, 20, 200),
        'cost' => fake()->randomFloat(2, 5, 80),
    ]);

    // Product without cost (should be excluded)
    Product::factory()->create([
        'price' => 100.00,
        'cost' => null,
    ]);

    Livewire::test(ProductMarginAnalysisChart::class)
        ->assertOk();
});

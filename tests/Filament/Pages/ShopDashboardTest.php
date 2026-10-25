<?php

use App\Enums\OrderStatus;
use App\Filament\Pages\ShopDashboard;
use App\Filament\Widgets\CustomerGrowthChart;
use App\Filament\Widgets\CustomerSegmentsChart;
use App\Filament\Widgets\FeaturesOverview;
use App\Filament\Widgets\FlaggedOrders;
use App\Filament\Widgets\OrdersYearOverYearChart;
use App\Filament\Widgets\OrderValueDistributionChart;
use App\Filament\Widgets\ProductMarginAnalysisChart;
use App\Filament\Widgets\ShopKpisStats;
use App\Filament\Widgets\TopProductsByRevenueChart;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use Livewire\Livewire;

it('renders the shop dashboard page with all widgets', function () {
    $categories = ProductCategory::factory()->count(3)->create();
    $customers = Customer::factory()->count(5)->create();
    $products = Product::factory()->count(5)->create();

    foreach ($products as $product) {
        $product->productCategories()->attach(
            $categories->random(rand(1, 3))->pluck('id'),
            ['created_at' => now(), 'updated_at' => now()],
        );
    }

    $orders = Order::factory()->count(10)->sequence(
        fn () => ['customer_id' => $customers->random()->id],
    )->create();

    foreach ($orders as $order) {
        OrderItem::factory()->count(rand(1, 3))->create([
            'order_id' => $order->id,
            'product_id' => $products->random()->id,
        ]);
    }

    $this->get(ShopDashboard::getUrl())
        ->assertOk();
});

it('renders shop widgets without polling', function (string $widget) {
    Livewire::withoutLazyLoading()
        ->test($widget)
        ->assertOk()
        ->assertDontSeeHtml('wire:poll');
})->with([
    ShopKpisStats::class,
    OrdersYearOverYearChart::class,
    CustomerGrowthChart::class,
    FlaggedOrders::class,
    TopProductsByRevenueChart::class,
    CustomerSegmentsChart::class,
    OrderValueDistributionChart::class,
    ProductMarginAnalysisChart::class,
]);

it('renders shop stats with page filters without polling', function () {
    Order::factory()->create(['status' => OrderStatus::New]);
    Order::factory()->create(['status' => OrderStatus::Delivered]);

    Livewire::withoutLazyLoading()
        ->test(ShopKpisStats::class)
        ->assertSee('2 orders')
        ->assertDontSeeHtml('wire:poll');

    Livewire::withoutLazyLoading()
        ->test(ShopKpisStats::class, [
            'pageFilters' => ['orderStatuses' => [OrderStatus::New->value]],
        ])
        ->assertSee('1 orders')
        ->assertDontSee('2 orders')
        ->assertDontSeeHtml('wire:poll');
});

it('does not advertise polling in the features overview', function () {
    Livewire::test(FeaturesOverview::class)
        ->assertOk()
        ->assertSee('Tables & Columns')
        ->assertDontSee('Live polling')
        ->assertDontSee('Table data auto-refreshes every 30 seconds in the background');
});

<?php

use App\Filament\Widgets\TopProductsByRevenueChart;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use Livewire\Livewire;

it('renders the top products by revenue widget', function () {
    $customer = Customer::factory()->create();
    $products = Product::factory()->count(5)->create();
    $orders = Order::factory()->count(3)->create([
        'customer_id' => $customer->id,
    ]);

    foreach ($orders as $order) {
        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id,
            'product_id' => $products->random()->id,
        ]);
    }

    Livewire::test(TopProductsByRevenueChart::class)
        ->assertOk();
});

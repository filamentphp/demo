<?php

use App\Enums\OrderStatus;
use App\Filament\Widgets\ShopKpisStats;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use Livewire\Livewire;

it('renders the shop KPIs widget', function () {
    $customers = Customer::factory()->count(5)->create();

    $products = Product::factory()->count(3)->create();

    // Give 2 customers multiple orders (repeat customers)
    foreach ($customers->take(2) as $customer) {
        $orders = Order::factory()->count(3)->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::Delivered,
            'total_price' => fake()->randomFloat(2, 100, 500),
        ]);

        foreach ($orders as $order) {
            OrderItem::factory()->count(2)->create([
                'order_id' => $order->id,
                'product_id' => $products->random()->id,
            ]);
        }
    }

    // Give remaining customers single orders
    foreach ($customers->skip(2)->take(2) as $customer) {
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::Shipped,
            'total_price' => fake()->randomFloat(2, 50, 200),
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $products->random()->id,
        ]);
    }

    // One cancelled order
    Order::factory()->create([
        'customer_id' => $customers->last()->id,
        'status' => OrderStatus::Cancelled,
        'total_price' => 150.00,
    ]);

    Livewire::test(ShopKpisStats::class)
        ->assertOk();
});

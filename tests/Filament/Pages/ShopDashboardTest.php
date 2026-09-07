<?php

use App\Filament\Pages\ShopDashboard;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;

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

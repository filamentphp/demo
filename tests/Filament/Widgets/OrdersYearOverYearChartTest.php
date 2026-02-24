<?php

use App\Filament\Widgets\OrdersYearOverYearChart;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use Livewire\Livewire;

it('renders the orders year-over-year widget', function () {
    $customer = Customer::factory()->create();

    // Current year orders spread across months
    Order::factory()->count(5)->create([
        'customer_id' => $customer->id,
        'created_at' => fake()->dateTimeBetween(
            now()->startOfYear(),
            now(),
        ),
    ]);

    // Previous year orders
    Order::factory()->count(5)->create([
        'customer_id' => $customer->id,
        'created_at' => fake()->dateTimeBetween(
            now()->subYear()->startOfYear(),
            now()->subYear()->endOfYear(),
        ),
    ]);

    Livewire::test(OrdersYearOverYearChart::class)
        ->assertOk();
});

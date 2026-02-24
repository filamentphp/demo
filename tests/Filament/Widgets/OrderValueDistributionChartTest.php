<?php

use App\Filament\Widgets\OrderValueDistributionChart;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use Livewire\Livewire;

it('renders the order value distribution widget', function () {
    $customer = Customer::factory()->create();

    // Orders across different price ranges
    Order::factory()->create(['customer_id' => $customer->id, 'total_price' => 25.00]);
    Order::factory()->create(['customer_id' => $customer->id, 'total_price' => 75.00]);
    Order::factory()->create(['customer_id' => $customer->id, 'total_price' => 150.00]);
    Order::factory()->create(['customer_id' => $customer->id, 'total_price' => 400.00]);
    Order::factory()->create(['customer_id' => $customer->id, 'total_price' => 750.00]);
    Order::factory()->create(['customer_id' => $customer->id, 'total_price' => 1500.00]);

    Livewire::test(OrderValueDistributionChart::class)
        ->assertOk();
});

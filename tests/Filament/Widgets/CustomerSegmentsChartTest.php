<?php

use App\Filament\Widgets\CustomerSegmentsChart;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use Livewire\Livewire;

it('renders the customer segments widget', function () {
    $customers = Customer::factory()->count(6)->create();

    // One-time customer (1 order)
    Order::factory()->create(['customer_id' => $customers[0]->id]);

    // Occasional customer (2 orders)
    Order::factory()->count(2)->create(['customer_id' => $customers[1]->id]);

    // Regular customer (5 orders)
    Order::factory()->count(5)->create(['customer_id' => $customers[2]->id]);

    // VIP customer (10 orders)
    Order::factory()->count(10)->create(['customer_id' => $customers[3]->id]);

    // No orders (should be excluded from segments)
    // $customers[4] and $customers[5] have 0 orders

    Livewire::test(CustomerSegmentsChart::class)
        ->assertOk();
});

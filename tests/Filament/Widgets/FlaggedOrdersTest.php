<?php

use App\Enums\OrderStatus;
use App\Filament\Widgets\FlaggedOrders;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use Livewire\Livewire;

it('renders the flagged orders widget', function () {
    $customer = Customer::factory()->create();

    // New order older than 3 days (should be flagged)
    Order::factory()->create([
        'customer_id' => $customer->id,
        'status' => OrderStatus::New,
        'created_at' => now()->subDays(5),
    ]);

    // Processing order older than 7 days (should be flagged)
    Order::factory()->create([
        'customer_id' => $customer->id,
        'status' => OrderStatus::Processing,
        'created_at' => now()->subDays(10),
    ]);

    // Recent new order (should NOT be flagged)
    Order::factory()->create([
        'customer_id' => $customer->id,
        'status' => OrderStatus::New,
        'created_at' => now()->subDay(),
    ]);

    Livewire::test(FlaggedOrders::class)
        ->assertOk()
        ->assertCanSeeTableRecords(
            Order::where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('status', OrderStatus::New)
                        ->where('created_at', '<=', now()->subDays(3));
                })->orWhere(function ($q2) {
                    $q2->where('status', OrderStatus::Processing)
                        ->where('created_at', '<=', now()->subDays(7));
                });
            })->get()
        )
        ->assertCanNotSeeTableRecords(
            Order::where('status', OrderStatus::New)
                ->where('created_at', '>', now()->subDays(3))
                ->get()
        );
});

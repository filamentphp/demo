<?php

use App\Enums\OrderStatus;
use App\Filament\Resources\Shop\Orders\Pages\ListOrders;
use App\Models\Shop\Order;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can render the list page', function () {
    $records = Order::factory()->count(3)->create();

    Livewire::test(ListOrders::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('can process a new order', function () {
    $record = Order::factory()->create(['status' => OrderStatus::New]);

    Livewire::test(ListOrders::class)
        ->callAction(TestAction::make('process')->table($record))
        ->assertNotified();

    $this->assertDatabaseHas(Order::class, ['id' => $record->id, 'status' => OrderStatus::Processing]);
});

it('process is hidden for non-new orders', function () {
    $record = Order::factory()->create(['status' => OrderStatus::Processing]);

    Livewire::test(ListOrders::class)
        ->assertActionHidden(TestAction::make('process')->table($record));
});

it('can ship a processing order', function () {
    $record = Order::factory()->create(['status' => OrderStatus::Processing]);

    Livewire::test(ListOrders::class)
        ->callAction(TestAction::make('ship')->table($record), [
            'notes' => 'Handle with care',
        ])
        ->assertNotified();

    $this->assertDatabaseHas(Order::class, ['id' => $record->id, 'status' => OrderStatus::Shipped]);
});

it('ship is hidden for non-processing orders', function () {
    $record = Order::factory()->create(['status' => OrderStatus::New]);

    Livewire::test(ListOrders::class)
        ->assertActionHidden(TestAction::make('ship')->table($record));
});

it('can deliver a shipped order', function () {
    $record = Order::factory()->create(['status' => OrderStatus::Shipped]);

    Livewire::test(ListOrders::class)
        ->callAction(TestAction::make('deliver')->table($record))
        ->assertNotified();

    $this->assertDatabaseHas(Order::class, ['id' => $record->id, 'status' => OrderStatus::Delivered]);
});

it('deliver is hidden for non-shipped orders', function () {
    $record = Order::factory()->create(['status' => OrderStatus::New]);

    Livewire::test(ListOrders::class)
        ->assertActionHidden(TestAction::make('deliver')->table($record));
});

it('can cancel an order', function () {
    $record = Order::factory()->create(['status' => OrderStatus::New]);

    Livewire::test(ListOrders::class)
        ->callAction(TestAction::make('cancel')->table($record))
        ->assertNotified();

    $this->assertDatabaseHas(Order::class, ['id' => $record->id, 'status' => OrderStatus::Cancelled]);
});

it('cancel is hidden for delivered orders', function () {
    $record = Order::factory()->create(['status' => OrderStatus::Delivered]);

    Livewire::test(ListOrders::class)
        ->assertActionHidden(TestAction::make('cancel')->table($record));
});

it('cannot cancel a shipped order', function () {
    $record = Order::factory()->create(['status' => OrderStatus::Shipped]);

    Livewire::test(ListOrders::class)
        ->assertActionDisabled(TestAction::make('cancel')->table($record));

    $record->refresh();
    expect($record->status)->toBe(OrderStatus::Shipped);
});

it('saves shipping notes when shipping an order', function () {
    $record = Order::factory()->create(['status' => OrderStatus::Processing]);

    Livewire::test(ListOrders::class)
        ->callAction(TestAction::make('ship')->table($record), [
            'notes' => 'Handle with care',
        ])
        ->assertNotified();

    $this->assertDatabaseHas(Order::class, [
        'id' => $record->id,
        'notes' => 'Handle with care',
    ]);
});

it('can delete an order', function () {
    $record = Order::factory()->create();

    Livewire::test(ListOrders::class)
        ->callAction(TestAction::make(DeleteAction::class)->table($record))
        ->assertNotified();
});

it('can bulk delete orders', function () {
    $records = Order::factory()->count(3)->create();

    Livewire::test(ListOrders::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();
});

it('can filter trashed orders', function () {
    $activeOrder = Order::factory()->create();
    $trashedOrder = Order::factory()->create();
    $trashedOrder->delete();

    Livewire::test(ListOrders::class)
        ->assertCanSeeTableRecords([$activeOrder])
        ->assertCanNotSeeTableRecords([$trashedOrder])
        ->filterTable('trashed', true)
        ->assertCanSeeTableRecords([$trashedOrder]);
});

it('can filter orders by created date range', function () {
    $oldOrder = Order::factory()->create(['created_at' => now()->subMonths(3)]);
    $recentOrder = Order::factory()->create(['created_at' => now()->subDay()]);

    Livewire::test(ListOrders::class)
        ->filterTable('created_at', [
            'created_from' => now()->subWeek()->toDateString(),
            'created_until' => now()->toDateString(),
        ])
        ->assertCanSeeTableRecords([$recentOrder])
        ->assertCanNotSeeTableRecords([$oldOrder]);
});

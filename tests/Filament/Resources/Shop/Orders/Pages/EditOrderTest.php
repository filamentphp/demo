<?php

use App\Enums\CurrencyCode;
use App\Enums\OrderStatus;
use App\Filament\Resources\Shop\Orders\Pages\EditOrder;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use Filament\Actions\ReplicateAction;
use Filament\Forms\Components\Repeater;
use Livewire\Livewire;

it('can render the edit page', function () {
    $record = Order::factory()->create();

    Livewire::test(EditOrder::class, ['record' => $record->getRouteKey()])
        ->assertOk();
});

it('can update a record', function () {
    $undoRepeaterFake = Repeater::fake();

    $customer = Customer::factory()->create();
    $product = Product::factory()->create();
    $record = Order::factory()->create(['customer_id' => $customer->id, 'currency' => CurrencyCode::Usd->value]);
    $record->orderItems()->create([
        'product_id' => $product->id,
        'qty' => 1,
        'unit_price' => $product->price,
        'sort' => 0,
    ]);

    Livewire::test(EditOrder::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'notes' => 'Updated order notes',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $this->assertDatabaseHas(Order::class, [
        'id' => $record->id,
        'notes' => '<p>Updated order notes</p>',
    ]);

    $undoRepeaterFake();
});

it('can replicate an order with new number and reset status', function () {
    $undoRepeaterFake = Repeater::fake();

    $customer = Customer::factory()->create();
    $product = Product::factory()->create();
    $record = Order::factory()->create([
        'customer_id' => $customer->id,
        'status' => OrderStatus::Processing,
        'currency' => CurrencyCode::Usd->value,
    ]);
    $record->orderItems()->create([
        'product_id' => $product->id,
        'qty' => 1,
        'unit_price' => $product->price,
        'sort' => 0,
    ]);

    Livewire::test(EditOrder::class, ['record' => $record->getRouteKey()])
        ->callAction(ReplicateAction::class)
        ->assertNotified();

    $replicated = Order::where('id', '!=', $record->id)->latest('id')->first();
    expect($replicated)->not->toBeNull();
    expect($replicated->status)->toBe(OrderStatus::New);
    expect($replicated->number)->toStartWith('OR-');
    expect($replicated->customer_id)->toBe($customer->id);

    $undoRepeaterFake();
});

it('validates the form data', function (array $data, array $errors) {
    $customer = Customer::factory()->create();
    $product = Product::factory()->create();
    $record = Order::factory()->create(['customer_id' => $customer->id, 'currency' => CurrencyCode::Usd->value]);
    $record->orderItems()->create([
        'product_id' => $product->id,
        'qty' => 1,
        'unit_price' => $product->price,
        'sort' => 0,
    ]);

    Livewire::test(EditOrder::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            ...$data,
        ])
        ->call('save')
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`customer_id` is required' => [['customer_id' => null], ['customer_id' => 'required']],
    '`status` is required' => [['status' => null], ['status' => 'required']],
    '`currency` is required' => [['currency' => null], ['currency' => 'required']],
]);

it('validates order item numeric fields', function (array $itemData, array $errors) {
    $undoRepeaterFake = Repeater::fake();

    $customer = Customer::factory()->create();
    $product = Product::factory()->create();
    $record = Order::factory()->create(['customer_id' => $customer->id, 'currency' => CurrencyCode::Usd->value]);
    $record->orderItems()->create([
        'product_id' => $product->id,
        'qty' => 1,
        'unit_price' => $product->price,
        'sort' => 0,
    ]);

    Livewire::test(EditOrder::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'items' => [
                [
                    'product_id' => $product->id,
                    'qty' => 2,
                    'unit_price' => $product->price,
                    ...$itemData,
                ],
            ],
        ])
        ->call('save')
        ->assertHasFormErrors($errors);

    $undoRepeaterFake();
})->with([
    '`qty` must be at least 1' => [['qty' => 0], ['items.0.qty' => 'min']],
    '`qty` must not be negative' => [['qty' => -1], ['items.0.qty' => 'min']],
    '`qty` must be an integer' => [['qty' => 1.5], ['items.0.qty' => 'integer']],
]);

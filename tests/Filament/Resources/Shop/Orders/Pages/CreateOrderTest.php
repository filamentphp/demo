<?php

use App\Enums\CurrencyCode;
use App\Enums\OrderStatus;
use App\Filament\Resources\Shop\Orders\Pages\CreateOrder;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use Filament\Forms\Components\Repeater;
use Livewire\Livewire;

it('can render the create page', function () {
    Livewire::test(CreateOrder::class)
        ->assertOk();
});

it('can create a record', function () {
    $undoRepeaterFake = Repeater::fake();

    $customer = Customer::factory()->create();
    $product = Product::factory()->create();

    Livewire::test(CreateOrder::class)
        ->fillForm([
            'customer_id' => $customer->id,
            'status' => OrderStatus::New,
            'currency' => CurrencyCode::Usd,
            'items' => [
                [
                    'product_id' => $product->id,
                    'qty' => 2,
                    'unit_price' => $product->price,
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $this->assertDatabaseHas(Order::class, ['customer_id' => $customer->id]);

    $undoRepeaterFake();
});

it('sends database notification after order creation', function () {
    $undoRepeaterFake = Repeater::fake();

    $customer = Customer::factory()->create();
    $product = Product::factory()->create();

    Livewire::test(CreateOrder::class)
        ->fillForm([
            'customer_id' => $customer->id,
            'status' => OrderStatus::New,
            'currency' => CurrencyCode::Usd,
            'items' => [
                [
                    'product_id' => $product->id,
                    'qty' => 2,
                    'unit_price' => $product->price,
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $user = auth()->user();
    expect($user->notifications)->toHaveCount(1);
    expect($user->notifications->first()->data['title'])->toBe('New order');

    $undoRepeaterFake();
});

it('validates order item numeric fields', function (array $itemData, array $errors) {
    $undoRepeaterFake = Repeater::fake();

    $customer = Customer::factory()->create();
    $product = Product::factory()->create();

    Livewire::test(CreateOrder::class)
        ->fillForm([
            'customer_id' => $customer->id,
            'status' => OrderStatus::New,
            'currency' => CurrencyCode::Usd,
            'items' => [
                [
                    'product_id' => $product->id,
                    'qty' => 2,
                    'unit_price' => $product->price,
                    ...$itemData,
                ],
            ],
        ])
        ->call('create')
        ->assertHasFormErrors($errors);

    $undoRepeaterFake();
})->with([
    '`qty` must be at least 1' => [['qty' => 0], ['items.0.qty' => 'min']],
    '`qty` must not be negative' => [['qty' => -1], ['items.0.qty' => 'min']],
    '`qty` must be an integer' => [['qty' => 1.5], ['items.0.qty' => 'integer']],
]);

it('validates the form data', function (array $data, array $errors) {
    $customer = Customer::factory()->create();

    Livewire::test(CreateOrder::class)
        ->fillForm([
            'customer_id' => $customer->id,
            'status' => OrderStatus::New,
            'currency' => CurrencyCode::Usd,
            ...$data,
        ])
        ->call('create')
        ->assertHasFormErrors($errors)
        ->assertNotNotified()
        ->assertNoRedirect();
})->with([
    '`customer_id` is required' => [['customer_id' => null], ['customer_id' => 'required']],
    '`status` is required' => [['status' => null], ['status' => 'required']],
    '`currency` is required' => [['currency' => null], ['currency' => 'required']],
]);

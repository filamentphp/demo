<?php

use App\Filament\Resources\Shop\Customers\Pages\CreateCustomer;
use App\Models\Shop\Customer;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the create page', function () {
    Livewire::test(CreateCustomer::class)
        ->assertOk();
});

it('can create a record', function () {
    $data = Customer::factory()->make();

    Livewire::test(CreateCustomer::class)
        ->fillForm([
            'name' => $data->name,
            'email' => $data->email,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    $this->assertDatabaseHas(Customer::class, ['email' => $data->email]);
});

it('validates the form data', function (array $data, array $errors) {
    $newData = Customer::factory()->make();

    Livewire::test(CreateCustomer::class)
        ->fillForm([
            'name' => $newData->name,
            'email' => $newData->email,
            ...$data,
        ])
        ->call('create')
        ->assertHasFormErrors($errors)
        ->assertNotNotified()
        ->assertNoRedirect();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
    '`email` is required' => [['email' => null], ['email' => 'required']],
    '`email` must be valid' => [['email' => Str::random()], ['email' => 'email']],
]);

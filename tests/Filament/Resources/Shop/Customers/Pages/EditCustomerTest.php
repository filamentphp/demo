<?php

use App\Filament\Resources\Shop\Customers\Pages\EditCustomer;
use App\Models\Shop\Customer;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the edit page', function () {
    $record = Customer::factory()->create();

    Livewire::test(EditCustomer::class, ['record' => $record->getRouteKey()])
        ->assertOk();
});

it('can update a record', function () {
    $record = Customer::factory()->create();
    $newData = Customer::factory()->make();

    Livewire::test(EditCustomer::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'name' => $newData->name,
            'email' => $newData->email,
        ])
        ->call('save')
        ->assertNotified();

    $this->assertDatabaseHas(Customer::class, [
        'id' => $record->id,
        'name' => $newData->name,
    ]);
});

it('can send email from edit page', function () {
    $record = Customer::factory()->create();

    Livewire::test(EditCustomer::class, ['record' => $record->getRouteKey()])
        ->callAction('send_email', [
            'to' => $record->email,
            'subject' => 'Test subject',
            'body' => 'Test body',
        ])
        ->assertNotified("Email sent to {$record->name}");
});

it('validates the form data', function (array $data, array $errors) {
    $record = Customer::factory()->create();
    $newData = Customer::factory()->make();

    Livewire::test(EditCustomer::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'name' => $newData->name,
            'email' => $newData->email,
            ...$data,
        ])
        ->call('save')
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
    '`email` is required' => [['email' => null], ['email' => 'required']],
    '`email` must be valid' => [['email' => Str::random()], ['email' => 'email']],
]);

<?php

use App\Filament\Resources\Shop\Customers\Pages\ListCustomers;
use App\Models\Address;
use App\Models\Shop\Customer;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can render the list page', function () {
    $records = Customer::factory()->count(3)->create();

    Livewire::test(ListCustomers::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('can render the country column for customers with addresses', function () {
    $customer = Customer::factory()->create();
    $address = Address::factory()->create();
    $customer->addresses()->attach($address);

    Livewire::test(ListCustomers::class)
        ->assertOk()
        ->assertCanSeeTableRecords([$customer]);
});

it('can send email to customer', function () {
    $record = Customer::factory()->create();

    Livewire::test(ListCustomers::class)
        ->callAction(TestAction::make('send_email')->table($record), [
            'subject' => 'Test Subject',
            'body' => '<p>Test body</p>',
        ])
        ->assertNotified();
});

it('can bulk delete customers', function () {
    $records = Customer::factory()->count(3)->create();

    Livewire::test(ListCustomers::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();
});

it('can filter trashed customers', function () {
    $activeCustomer = Customer::factory()->create();
    $trashedCustomer = Customer::factory()->create();
    $trashedCustomer->delete();

    Livewire::test(ListCustomers::class)
        ->assertCanSeeTableRecords([$activeCustomer])
        ->assertCanNotSeeTableRecords([$trashedCustomer])
        ->filterTable('trashed', true)
        ->assertCanSeeTableRecords([$trashedCustomer]);
});

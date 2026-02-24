<?php

use App\Filament\Resources\Shop\Categories\Pages\CreateCategory;
use App\Models\Shop\ProductCategory;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the create page', function () {
    Livewire::test(CreateCategory::class)
        ->assertOk();
});

it('can create a record', function () {
    $data = ProductCategory::factory()->make();

    Livewire::test(CreateCategory::class)
        ->fillForm([
            'name' => $data->name,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    $this->assertDatabaseHas(ProductCategory::class, ['name' => $data->name]);
});

it('validates the form data', function (array $data, array $errors) {
    $newData = ProductCategory::factory()->make();

    Livewire::test(CreateCategory::class)
        ->fillForm([
            'name' => $newData->name,
            ...$data,
        ])
        ->call('create')
        ->assertHasFormErrors($errors)
        ->assertNotNotified()
        ->assertNoRedirect();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
]);

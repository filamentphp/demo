<?php

use App\Filament\Resources\Shop\Brands\Pages\CreateBrand;
use App\Models\Shop\Brand;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the create page', function () {
    Livewire::test(CreateBrand::class)
        ->assertOk();
});

it('can create a record', function () {
    $data = Brand::factory()->make();

    Livewire::test(CreateBrand::class)
        ->fillForm([
            'name' => $data->name,
            'website' => $data->website,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    $this->assertDatabaseHas(Brand::class, ['name' => $data->name]);
});

it('validates the form data', function (array $data, array $errors) {
    $newData = Brand::factory()->make();

    Livewire::test(CreateBrand::class)
        ->fillForm([
            'name' => $newData->name,
            'website' => $newData->website,
            ...$data,
        ])
        ->call('create')
        ->assertHasFormErrors($errors)
        ->assertNotNotified()
        ->assertNoRedirect();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
    '`website` is required' => [['website' => null], ['website' => 'required']],
    '`website` must be a valid URL' => [['website' => 'not-a-url'], ['website' => 'url']],
]);

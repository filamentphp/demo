<?php

use App\Filament\Resources\Shop\Categories\Pages\EditCategory;
use App\Models\Shop\ProductCategory;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the edit page', function () {
    $record = ProductCategory::factory()->create();

    Livewire::test(EditCategory::class, ['record' => $record->getRouteKey()])
        ->assertOk();
});

it('can update a record', function () {
    $record = ProductCategory::factory()->create();
    $newData = ProductCategory::factory()->make();

    Livewire::test(EditCategory::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'name' => $newData->name,
        ])
        ->call('save')
        ->assertNotified();

    $this->assertDatabaseHas(ProductCategory::class, [
        'id' => $record->id,
        'name' => $newData->name,
    ]);
});

it('validates the form data', function (array $data, array $errors) {
    $record = ProductCategory::factory()->create();
    $newData = ProductCategory::factory()->make();

    Livewire::test(EditCategory::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'name' => $newData->name,
            ...$data,
        ])
        ->call('save')
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
]);

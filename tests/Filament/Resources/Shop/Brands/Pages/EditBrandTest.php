<?php

use App\Filament\Resources\Shop\Brands\Pages\EditBrand;
use App\Models\Shop\Brand;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the edit page', function () {
    $record = Brand::factory()->create();

    Livewire::test(EditBrand::class, ['record' => $record->getRouteKey()])
        ->assertOk();
});

it('can update a record', function () {
    $record = Brand::factory()->create();
    $newData = Brand::factory()->make();

    Livewire::test(EditBrand::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'name' => $newData->name,
            'website' => $newData->website,
        ])
        ->call('save')
        ->assertNotified();

    $this->assertDatabaseHas(Brand::class, [
        'id' => $record->id,
        'name' => $newData->name,
    ]);
});

it('shows visit website action when brand has website', function () {
    $record = Brand::factory()->create(['website' => 'https://example.com']);

    Livewire::test(EditBrand::class, ['record' => $record->getRouteKey()])
        ->assertActionVisible('visit_website');
});

it('hides visit website action when brand has no website', function () {
    $record = Brand::factory()->create(['website' => null]);

    Livewire::test(EditBrand::class, ['record' => $record->getRouteKey()])
        ->assertActionHidden('visit_website');
});

it('validates the form data', function (array $data, array $errors) {
    $record = Brand::factory()->create();
    $newData = Brand::factory()->make();

    Livewire::test(EditBrand::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'name' => $newData->name,
            'website' => $newData->website,
            ...$data,
        ])
        ->call('save')
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
    '`website` is required' => [['website' => null], ['website' => 'required']],
    '`website` must be a valid URL' => [['website' => 'not-a-url'], ['website' => 'url']],
]);

<?php

use App\Filament\Resources\Shop\Products\Pages\CreateProduct;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the create page', function () {
    Livewire::test(CreateProduct::class)
        ->assertOk();
});

it('can create a record', function () {
    $data = Product::factory()->make();
    $category = ProductCategory::factory()->create();

    Livewire::test(CreateProduct::class)
        ->fillForm([
            'name' => $data->name,
            'price' => $data->price,
            'old_price' => $data->old_price,
            'cost' => $data->cost,
            'sku' => $data->sku,
            'barcode' => $data->barcode,
            'qty' => $data->qty,
            'security_stock' => $data->security_stock,
            'published_at' => $data->published_at,
            'productCategories' => [$category->id],
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    $this->assertDatabaseHas(Product::class, ['sku' => $data->sku]);
});

it('validates the form data', function (array $data, array $errors) {
    $newData = Product::factory()->make();
    $category = ProductCategory::factory()->create();

    Livewire::test(CreateProduct::class)
        ->fillForm([
            'name' => $newData->name,
            'price' => $newData->price,
            'old_price' => $newData->old_price,
            'cost' => $newData->cost,
            'sku' => $newData->sku,
            'barcode' => $newData->barcode,
            'qty' => $newData->qty,
            'security_stock' => $newData->security_stock,
            'published_at' => $newData->published_at,
            'productCategories' => [$category->id],
            ...$data,
        ])
        ->call('create')
        ->assertHasFormErrors($errors)
        ->assertNotNotified()
        ->assertNoRedirect();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
    '`price` is required' => [['price' => null], ['price' => 'required']],
    '`old_price` is required' => [['old_price' => null], ['old_price' => 'required']],
    '`cost` is required' => [['cost' => null], ['cost' => 'required']],
    '`sku` is required' => [['sku' => null], ['sku' => 'required']],
    '`barcode` is required' => [['barcode' => null], ['barcode' => 'required']],
    '`qty` is required' => [['qty' => null], ['qty' => 'required']],
    '`security_stock` is required' => [['security_stock' => null], ['security_stock' => 'required']],
    '`published_at` is required' => [['published_at' => null], ['published_at' => 'required']],
    '`productCategories` is required' => [['productCategories' => null], ['productCategories' => 'required']],
    '`price` must not be negative' => [['price' => -1], ['price' => 'min']],
    '`price` must not exceed 99999999.99' => [['price' => 100000000], ['price' => 'max']],
    '`old_price` must not be negative' => [['old_price' => -1], ['old_price' => 'min']],
    '`old_price` must not exceed 99999999.99' => [['old_price' => 100000000], ['old_price' => 'max']],
    '`cost` must not be negative' => [['cost' => -1], ['cost' => 'min']],
    '`cost` must not exceed 99999999.99' => [['cost' => 100000000], ['cost' => 'max']],
    '`qty` must not be negative' => [['qty' => -1], ['qty' => 'min']],
    '`qty` must be an integer' => [['qty' => 1.5], ['qty' => 'integer']],
    '`security_stock` must not be negative' => [['security_stock' => -1], ['security_stock' => 'min']],
    '`security_stock` must be an integer' => [['security_stock' => 1.5], ['security_stock' => 'integer']],
]);

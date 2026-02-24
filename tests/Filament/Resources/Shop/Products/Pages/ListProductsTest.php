<?php

use App\Filament\Resources\Shop\Products\Pages\ListProducts;
use App\Models\Shop\Product;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can render the list page', function () {
    $records = Product::factory()->count(3)->create();

    Livewire::test(ListProducts::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('can toggle product visibility', function () {
    $record = Product::factory()->create(['is_visible' => true]);

    Livewire::test(ListProducts::class)
        ->callAction(TestAction::make('toggle_visibility')->table($record));

    $this->assertDatabaseHas(Product::class, ['id' => $record->id, 'is_visible' => false]);
});

it('can adjust product price', function () {
    $record = Product::factory()->create(['price' => 100, 'old_price' => 150]);

    Livewire::test(ListProducts::class)
        ->callAction(TestAction::make('adjust_price')->table($record), [
            'price' => 29.99,
            'old_price' => 39.99,
        ]);

    $this->assertDatabaseHas(Product::class, [
        'id' => $record->id,
        'price' => 29.99,
        'old_price' => 39.99,
    ]);
});

it('can adjust product stock', function () {
    $record = Product::factory()->create(['qty' => 10]);

    Livewire::test(ListProducts::class)
        ->callAction(TestAction::make('adjust_stock')->table($record), [
            'qty' => 100,
        ]);

    $this->assertDatabaseHas(Product::class, ['id' => $record->id, 'qty' => 100]);
});

it('can delete a product', function () {
    $record = Product::factory()->create();

    Livewire::test(ListProducts::class)
        ->callAction(TestAction::make(DeleteAction::class)->table($record))
        ->assertNotified();
});

it('can bulk toggle visibility', function () {
    $records = Product::factory()->count(3)->create(['is_visible' => true]);

    Livewire::test(ListProducts::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make('toggle_visibility')->table()->bulk(), [
            'is_visible' => '0',
        ]);

    foreach ($records as $record) {
        $this->assertDatabaseHas(Product::class, ['id' => $record->id, 'is_visible' => false]);
    }
});

it('can bulk delete products', function () {
    $records = Product::factory()->count(3)->create();

    Livewire::test(ListProducts::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();
});

it('has query builder filter', function () {
    Livewire::test(ListProducts::class)
        ->assertTableFilterExists('queryBuilder');
});

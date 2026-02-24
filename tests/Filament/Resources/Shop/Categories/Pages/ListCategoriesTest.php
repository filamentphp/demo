<?php

use App\Filament\Resources\Shop\Categories\Pages\ListCategories;
use App\Models\Shop\ProductCategory;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can render the list page', function () {
    $records = ProductCategory::factory()->count(3)->create();

    Livewire::test(ListCategories::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('can toggle category visibility', function () {
    $record = ProductCategory::factory()->create(['is_visible' => true]);

    Livewire::test(ListCategories::class)
        ->callAction(TestAction::make('toggle_visibility')->table($record));

    $this->assertDatabaseHas(ProductCategory::class, ['id' => $record->id, 'is_visible' => false]);
});

it('can bulk delete categories', function () {
    $records = ProductCategory::factory()->count(3)->create();

    Livewire::test(ListCategories::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();
});

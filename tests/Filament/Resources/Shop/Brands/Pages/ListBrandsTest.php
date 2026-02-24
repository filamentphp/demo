<?php

use App\Filament\Resources\Shop\Brands\Pages\ListBrands;
use App\Models\Shop\Brand;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can render the list page', function () {
    $records = Brand::factory()->count(3)->create();

    Livewire::test(ListBrands::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('can toggle brand visibility', function () {
    $record = Brand::factory()->create(['is_visible' => true]);

    Livewire::test(ListBrands::class)
        ->callAction(TestAction::make('toggle_visibility')->table($record));

    $this->assertDatabaseHas(Brand::class, ['id' => $record->id, 'is_visible' => false]);
});

it('can bulk delete brands', function () {
    $records = Brand::factory()->count(3)->create();

    Livewire::test(ListBrands::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();
});

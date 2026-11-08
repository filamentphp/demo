<?php

use App\Filament\DemoIconAlias;
use App\Filament\Resources\Shop\Brands\Pages\ListBrands;
use App\Models\Shop\Brand;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Support\HtmlString;
use Livewire\Livewire;

it('can render the list page', function () {
    $records = Brand::factory()->count(3)->create();

    Livewire::test(ListBrands::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('renders HTML icon overrides for both brand visibility states', function () {
    FilamentIcon::register([
        DemoIconAlias::RESOURCES_SHOP_BRANDS_ACTIONS_HIDE => new HtmlString('<svg data-test-icon="hide" aria-hidden="true"></svg>'),
        DemoIconAlias::RESOURCES_SHOP_BRANDS_ACTIONS_SHOW => new HtmlString('<svg data-test-icon="show" aria-hidden="true"></svg>'),
    ]);

    $records = Brand::factory()->createMany([
        ['is_visible' => true],
        ['is_visible' => false],
    ]);

    Livewire::test(ListBrands::class)
        ->assertCanSeeTableRecords($records)
        ->assertSeeHtml('data-test-icon="hide"')
        ->assertSeeHtml('data-test-icon="show"');
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

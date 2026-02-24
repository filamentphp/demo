<?php

use App\Filament\Resources\Blog\Categories\Pages\ManageCategories;
use App\Models\Blog\PostCategory;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the manage page', function () {
    $records = PostCategory::factory()->count(3)->create();

    Livewire::test(ManageCategories::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('can create a record', function () {
    $data = PostCategory::factory()->make();

    Livewire::test(ManageCategories::class)
        ->callAction(CreateAction::class, [
            'name' => $data->name,
        ])
        ->assertNotified();

    $this->assertDatabaseHas(PostCategory::class, ['name' => $data->name]);
});

it('can edit a record', function () {
    $record = PostCategory::factory()->create();
    $newData = PostCategory::factory()->make();

    Livewire::test(ManageCategories::class)
        ->callAction(
            TestAction::make(EditAction::class)->table($record),
            ['name' => $newData->name],
        )
        ->assertNotified();

    $this->assertDatabaseHas(PostCategory::class, ['id' => $record->id, 'name' => $newData->name]);
});

it('validates create action data', function (array $data, array $errors) {
    $validData = PostCategory::factory()->make();

    Livewire::test(ManageCategories::class)
        ->callAction(CreateAction::class, [
            'name' => $validData->name,
            ...$data,
        ])
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
]);

it('validates edit action data', function (array $data, array $errors) {
    $record = PostCategory::factory()->create();
    $newData = PostCategory::factory()->make();

    Livewire::test(ManageCategories::class)
        ->callAction(
            TestAction::make(EditAction::class)->table($record),
            ['name' => $newData->name, ...$data],
        )
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
]);

it('can toggle category visibility', function () {
    $record = PostCategory::factory()->create(['is_visible' => true]);

    Livewire::test(ManageCategories::class)
        ->callAction(TestAction::make('toggle_visibility')->table($record));

    $this->assertDatabaseHas(PostCategory::class, ['id' => $record->id, 'is_visible' => false]);
});

it('can delete a category', function () {
    $record = PostCategory::factory()->create();

    Livewire::test(ManageCategories::class)
        ->callAction(TestAction::make(DeleteAction::class)->table($record))
        ->assertNotified();
});

it('can bulk delete categories', function () {
    $records = PostCategory::factory()->count(3)->create();

    Livewire::test(ManageCategories::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();
});

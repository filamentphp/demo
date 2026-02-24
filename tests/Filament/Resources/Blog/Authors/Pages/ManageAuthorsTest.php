<?php

use App\Filament\Resources\Blog\Authors\Pages\ManageAuthors;
use App\Models\Blog\Author;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the manage page', function () {
    $records = Author::factory()->count(3)->create();

    Livewire::test(ManageAuthors::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('can create a record', function () {
    $data = Author::factory()->make();

    Livewire::test(ManageAuthors::class)
        ->callAction(CreateAction::class, [
            'name' => $data->name,
            'email' => $data->email,
        ])
        ->assertNotified();

    $this->assertDatabaseHas(Author::class, ['email' => $data->email]);
});

it('can edit a record', function () {
    $record = Author::factory()->create();
    $newData = Author::factory()->make();

    Livewire::test(ManageAuthors::class)
        ->callAction(
            TestAction::make(EditAction::class)->table($record),
            ['name' => $newData->name, 'email' => $newData->email],
        )
        ->assertNotified();

    $this->assertDatabaseHas(Author::class, ['id' => $record->id, 'name' => $newData->name]);
});

it('validates create action data', function (array $data, array $errors) {
    $validData = Author::factory()->make();

    Livewire::test(ManageAuthors::class)
        ->callAction(CreateAction::class, [
            'name' => $validData->name,
            'email' => $validData->email,
            ...$data,
        ])
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
    '`email` is required' => [['email' => null], ['email' => 'required']],
    '`email` must be valid' => [['email' => Str::random()], ['email' => 'email']],
]);

it('validates edit action data', function (array $data, array $errors) {
    $record = Author::factory()->create();
    $newData = Author::factory()->make();

    Livewire::test(ManageAuthors::class)
        ->callAction(
            TestAction::make(EditAction::class)->table($record),
            ['name' => $newData->name, 'email' => $newData->email, ...$data],
        )
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
    '`email` is required' => [['email' => null], ['email' => 'required']],
    '`email` must be valid' => [['email' => Str::random()], ['email' => 'email']],
]);

it('can delete an author', function () {
    $record = Author::factory()->create();

    Livewire::test(ManageAuthors::class)
        ->callAction(TestAction::make(DeleteAction::class)->table($record))
        ->assertNotified();
});

it('can bulk delete authors', function () {
    $records = Author::factory()->count(3)->create();

    Livewire::test(ManageAuthors::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();
});

<?php

use App\Filament\Resources\HR\Departments\Pages\ManageDepartments;
use App\Models\HR\Department;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the manage page', function () {
    $records = Department::factory()->count(3)->create();

    Livewire::test(ManageDepartments::class)
        ->assertOk()
        ->assertCanSeeTableRecords($records);
});

it('can create a record', function () {
    $data = Department::factory()->make();

    Livewire::test(ManageDepartments::class)
        ->callAction(CreateAction::class, [
            'name' => $data->name,
        ])
        ->assertNotified();

    $this->assertDatabaseHas(Department::class, ['name' => $data->name]);
});

it('can edit a record', function () {
    $record = Department::factory()->create();
    $newData = Department::factory()->make();

    Livewire::test(ManageDepartments::class)
        ->callAction(
            TestAction::make(EditAction::class)->table($record),
            ['name' => $newData->name],
        )
        ->assertNotified();

    $this->assertDatabaseHas(Department::class, ['id' => $record->id, 'name' => $newData->name]);
});

it('validates create action data', function (array $data, array $errors) {
    $validData = Department::factory()->make();

    Livewire::test(ManageDepartments::class)
        ->callAction(CreateAction::class, [
            'name' => $validData->name,
            ...$data,
        ])
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
    '`description` is max 65535 characters' => [['description' => Str::random(65536)], ['description' => 'max']],
    '`budget` is required' => [['budget' => null], ['budget' => 'required']],
    '`budget` must not exceed 9999999999.99' => [['budget' => 10000000000], ['budget' => 'max']],
    '`budget` must not be negative' => [['budget' => -1], ['budget' => 'min']],
    '`headcount_limit` is required' => [['headcount_limit' => null], ['headcount_limit' => 'required']],
    '`headcount_limit` must not exceed 2147483647' => [['headcount_limit' => 2147483648], ['headcount_limit' => 'max']],
    '`headcount_limit` must not be negative' => [['headcount_limit' => -1], ['headcount_limit' => 'min']],
]);

it('validates edit action data', function (array $data, array $errors) {
    $record = Department::factory()->create();
    $newData = Department::factory()->make();

    Livewire::test(ManageDepartments::class)
        ->callAction(
            TestAction::make(EditAction::class)->table($record),
            ['name' => $newData->name, ...$data],
        )
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
    '`description` is max 65535 characters' => [['description' => Str::random(65536)], ['description' => 'max']],
    '`budget` is required' => [['budget' => null], ['budget' => 'required']],
    '`budget` must not exceed 9999999999.99' => [['budget' => 10000000000], ['budget' => 'max']],
    '`budget` must not be negative' => [['budget' => -1], ['budget' => 'min']],
    '`headcount_limit` is required' => [['headcount_limit' => null], ['headcount_limit' => 'required']],
    '`headcount_limit` must not exceed 2147483647' => [['headcount_limit' => 2147483648], ['headcount_limit' => 'max']],
    '`headcount_limit` must not be negative' => [['headcount_limit' => -1], ['headcount_limit' => 'min']],
]);

it('can adjust department budget', function () {
    $record = Department::factory()->create(['budget' => 100000]);

    Livewire::test(ManageDepartments::class)
        ->callAction(TestAction::make('adjust_budget')->table($record), [
            'budget' => 50000,
        ]);

    $this->assertDatabaseHas(Department::class, ['id' => $record->id, 'budget' => 50000]);
});

it('can toggle department active status', function () {
    $record = Department::factory()->create(['is_active' => true]);

    Livewire::test(ManageDepartments::class)
        ->callAction(TestAction::make('toggle_active')->table($record));

    $this->assertDatabaseHas(Department::class, ['id' => $record->id, 'is_active' => false]);
});

it('can replicate a department', function () {
    $record = Department::factory()->create();

    Livewire::test(ManageDepartments::class)
        ->callAction(TestAction::make(ReplicateAction::class)->table($record));

    expect(Department::where('name', $record->name)->count())->toBe(2);
});

it('can delete a department', function () {
    $record = Department::factory()->create();

    Livewire::test(ManageDepartments::class)
        ->callAction(TestAction::make(DeleteAction::class)->table($record))
        ->assertNotified();
});

it('can bulk delete departments', function () {
    $records = Department::factory()->count(3)->create();

    Livewire::test(ManageDepartments::class)
        ->selectTableRecords($records)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified();
});

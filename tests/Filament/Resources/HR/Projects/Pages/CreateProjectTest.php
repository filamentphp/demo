<?php

use App\Enums\ProjectStatus;
use App\Enums\TaskPriority;
use App\Filament\Resources\HR\Projects\Pages\CreateProject;
use App\Models\HR\Project;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the create page', function () {
    Livewire::test(CreateProject::class)
        ->assertOk();
});

it('can create a record', function () {
    $data = Project::factory()->make();

    Livewire::test(CreateProject::class)
        ->fillForm([
            'name' => $data->name,
            'status' => ProjectStatus::Planning,
            'priority' => TaskPriority::Medium,
            'start_date' => $data->start_date,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    $this->assertDatabaseHas(Project::class, ['name' => $data->name]);
});

it('validates the form data', function (array $data, array $errors) {
    $newData = Project::factory()->make();

    Livewire::test(CreateProject::class)
        ->fillForm([
            'name' => $newData->name,
            'status' => ProjectStatus::Planning,
            'priority' => TaskPriority::Medium,
            'start_date' => $newData->start_date,
            ...$data,
        ])
        ->call('create')
        ->assertHasFormErrors($errors)
        ->assertNotNotified()
        ->assertNoRedirect();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
    '`status` is required' => [['status' => null], ['status' => 'required']],
    '`priority` is required' => [['priority' => null], ['priority' => 'required']],
    '`start_date` is required' => [['start_date' => null], ['start_date' => 'required']],
    '`budget` is required' => [['budget' => null], ['budget' => 'required']],
    '`budget` must not exceed 9999999999.99' => [['budget' => 10000000000], ['budget' => 'max']],
    '`budget` must not be negative' => [['budget' => -1], ['budget' => 'min']],
    '`estimated_hours` must not exceed 9999999.9' => [['estimated_hours' => 10000000], ['estimated_hours' => 'max']],
    '`estimated_hours` must not be negative' => [['estimated_hours' => -1], ['estimated_hours' => 'min']],
]);

<?php

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Filament\Resources\HR\Tasks\Pages\CreateTask;
use App\Models\HR\Project;
use App\Models\HR\Task;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the create page', function () {
    Livewire::test(CreateTask::class)
        ->assertOk();
});

it('can create a record', function () {
    $project = Project::factory()->create();
    $data = Task::factory()->make();

    Livewire::test(CreateTask::class)
        ->fillForm([
            'title' => $data->title,
            'project_id' => $project->id,
            'status' => TaskStatus::Backlog,
            'priority' => TaskPriority::Medium,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    $this->assertDatabaseHas(Task::class, ['title' => $data->title]);
});

it('validates the form data', function (array $data, array $errors) {
    $project = Project::factory()->create();
    $newData = Task::factory()->make();

    Livewire::test(CreateTask::class)
        ->fillForm([
            'title' => $newData->title,
            'project_id' => $project->id,
            'status' => TaskStatus::Backlog,
            'priority' => TaskPriority::Medium,
            ...$data,
        ])
        ->call('create')
        ->assertHasFormErrors($errors)
        ->assertNotNotified()
        ->assertNoRedirect();
})->with([
    '`title` is required' => [['title' => null], ['title' => 'required']],
    '`title` is max 255 characters' => [['title' => Str::random(256)], ['title' => 'max']],
    '`project_id` is required' => [['project_id' => null], ['project_id' => 'required']],
    '`status` is required' => [['status' => null], ['status' => 'required']],
    '`priority` is required' => [['priority' => null], ['priority' => 'required']],
    '`estimated_hours` must not exceed 99999.9' => [['estimated_hours' => 100000], ['estimated_hours' => 'max']],
    '`estimated_hours` must not be negative' => [['estimated_hours' => -1], ['estimated_hours' => 'min']],
]);

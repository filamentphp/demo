<?php

use App\Filament\Resources\HR\Tasks\Pages\EditTask;
use App\Models\HR\Project;
use App\Models\HR\Task;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('can render the edit page', function () {
    $project = Project::factory()->create();
    $record = Task::factory()->create(['project_id' => $project->id]);

    Livewire::test(EditTask::class, ['record' => $record->getRouteKey()])
        ->assertOk();
});

it('can update a record', function () {
    $project = Project::factory()->create();
    $record = Task::factory()->create(['project_id' => $project->id]);
    $newData = Task::factory()->make();

    Livewire::test(EditTask::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'title' => $newData->title,
        ])
        ->call('save')
        ->assertNotified();

    $this->assertDatabaseHas(Task::class, [
        'id' => $record->id,
        'title' => $newData->title,
    ]);
});

it('validates the form data', function (array $data, array $errors) {
    $project = Project::factory()->create();
    $record = Task::factory()->create(['project_id' => $project->id]);
    $newData = Task::factory()->make();

    Livewire::test(EditTask::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'title' => $newData->title,
            ...$data,
        ])
        ->call('save')
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`title` is required' => [['title' => null], ['title' => 'required']],
    '`title` is max 255 characters' => [['title' => Str::random(256)], ['title' => 'max']],
    '`project_id` is required' => [['project_id' => null], ['project_id' => 'required']],
    '`status` is required' => [['status' => null], ['status' => 'required']],
    '`priority` is required' => [['priority' => null], ['priority' => 'required']],
    '`estimated_hours` must not exceed 99999.9' => [['estimated_hours' => 100000], ['estimated_hours' => 'max']],
    '`estimated_hours` must not be negative' => [['estimated_hours' => -1], ['estimated_hours' => 'min']],
]);

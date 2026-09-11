<?php

use App\Livewire\ClientProjects;
use App\Models\HR\Project;
use Livewire\Livewire;

it('renders the standalone Filament forms and their fields', function (): void {
    $project = Project::factory()->create();

    Livewire::test(ClientProjects::class, ['projectId' => $project->id])
        ->assertSuccessful()
        ->assertFormFieldExists('projectId', 'selectorForm')
        ->assertFormFieldExists('name', 'projectForm')
        ->assertFormFieldExists('status', 'projectForm')
        ->assertFormFieldExists('priority', 'projectForm')
        ->assertFormFieldExists('budget', 'projectForm')
        ->assertFormFieldExists('spent', 'projectForm')
        ->assertFormFieldExists('taskTitle', 'taskForm')
        ->assertSee('Save changes')
        ->assertSee('Add task');
});

it('validates invalid project fields without writing them', function (): void {
    $project = Project::factory()->create(['name' => 'Original project']);

    Livewire::test(ClientProjects::class, ['projectId' => $project->id])
        ->set('data.name', '')
        ->set('data.status', 'not-a-status')
        ->set('data.budget', -1)
        ->set('data.spent', -1)
        ->call('save')
        ->assertHasFormErrors(['name', 'status', 'budget', 'spent'], 'projectForm');

    expect($project->refresh()->name)->toBe('Original project');
});

it('loads the selected project and clears drafts and validation', function (): void {
    $firstProject = Project::factory()->create(['name' => 'First project']);
    $secondProject = Project::factory()->create(['name' => 'Second project']);

    Livewire::test(ClientProjects::class, ['projectId' => $firstProject->id])
        ->set('data.name', 'Unsaved name')
        ->set('taskTitle', 'Unsaved task')
        ->set('data.spent', -1)
        ->call('save')
        ->assertHasErrors(['data.spent'])
        ->set('projectId', $secondProject->id)
        ->assertSet('data.name', 'Second project')
        ->assertSet('taskTitle', '')
        ->assertHasNoErrors();
});

it('validates project and task forms independently', function (): void {
    $project = Project::factory()->create();
    $component = Livewire::test(ClientProjects::class, ['projectId' => $project->id])
        ->set('data.name', '')
        ->set('taskTitle', '')
        ->call('addTask')
        ->assertHasFormErrors(['taskTitle' => 'required'], 'taskForm')
        ->assertHasNoErrors(['data.name']);

    expect($project->tasks()->count())->toBe(0);

    $component
        ->set('taskTitle', 'Unsaved valid task')
        ->call('save')
        ->assertHasFormErrors(['name' => 'required'], 'projectForm')
        ->assertHasNoErrors(['taskTitle']);

    expect($project->tasks()->count())->toBe(0);
});

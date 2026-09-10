<?php

use App\Enums\ProjectStatus;
use App\Filament\Resources\HR\Projects\Pages\EditProject;
use App\Livewire\ClientProjects;
use App\Models\HR\Project;
use Livewire\Livewire;

it('refreshes untouched fields but preserves unrelated local drafts and shared descriptions', function (): void {
    $project = Project::factory()->create(['name' => 'Original', 'budget' => 1000, 'status' => 'active']);
    $page = Livewire::test(EditProject::class, ['record' => $project->id])
        ->fillForm(['name' => 'Local draft', 'description' => '<p>Shared draft</p>']);
    $project->update(['budget' => 2750, 'status' => 'on_hold']);

    $page->dispatch('echo-private:projects,ProjectChanged', ['projectId' => $project->id])
        ->assertFormSet(['name' => 'Local draft', 'budget' => '2750.00', 'status' => ProjectStatus::OnHold])
        ->assertSet('conflicts', []);
    expect(json_encode($page->get('data.description')))->toContain('Shared draft');
});

it('detects client portal conflicts and requires a choice per field without saving', function (): void {
    $project = Project::factory()->create(['name' => 'Original', 'budget' => 1000]);
    $page = Livewire::test(EditProject::class, ['record' => $project->id])->fillForm(['name' => 'My draft', 'budget' => 1200]);
    Livewire::test(ClientProjects::class, ['projectId' => $project->id])
        ->set('data.name', 'Client version')->set('data.budget', 1500)->call('save');

    $page->dispatch('echo-private:projects,ProjectChanged', ['projectId' => $project->id])
        ->assertSet('conflicts.name.local', 'My draft')
        ->assertSet('conflicts.name.remote', 'Client version')
        ->assertNotified('Review conflicting changes')
        ->call('resolveConflicts')->assertHasErrors(['conflictChoices.name', 'conflictChoices.budget']);
    expect($page->get('conflicts.name.by'))->toContain('Client portal');

    $page->set('conflictChoices.name', 'local')->set('conflictChoices.budget', 'remote')
        ->call('resolveConflicts')->assertHasNoErrors()
        ->assertSet('conflicts', [])
        ->assertFormSet(['name' => 'My draft', 'budget' => '1500.00']);
    expect($project->refresh()->name)->toBe('Client version');
    $page->call('save')->assertHasNoFormErrors();
    expect($project->refresh())->name->toBe('My draft')->budget->toBe('1500.00');
});

it('checks the latest database values on save even before a broadcast arrives', function (): void {
    $project = Project::factory()->create(['name' => 'Original', 'budget' => 1000]);
    $page = Livewire::test(EditProject::class, ['record' => $project->id])->fillForm(['name' => 'Local']);
    $project->update(['name' => 'Remote', 'budget' => 3200]);

    $page->call('save')->assertDispatched('open-modal', id: 'project-conflicts')
        ->assertSet('conflicts.name.remote', 'Remote')
        ->assertFormSet(['budget' => '3200.00']);
    expect($project->refresh()->name)->toBe('Remote');
});

it('updates conflicts and invalidates only choices whose values changed', function (): void {
    $project = Project::factory()->create(['name' => 'Original', 'budget' => 1000]);
    $page = Livewire::test(EditProject::class, ['record' => $project->id])->fillForm(['name' => 'Local', 'budget' => 2000]);
    $project->update(['name' => 'Remote one', 'budget' => 3000]);
    $page->dispatch('echo-private:projects,ProjectChanged', ['projectId' => $project->id])
        ->set('conflictChoices.name', 'remote')->set('conflictChoices.budget', 'local');
    $project->update(['name' => 'Remote two']);

    $page->call('resolveConflicts')->assertHasErrors(['conflictChoices.name'])
        ->assertSet('conflictChoices.budget', 'local')
        ->assertSet('conflicts.name.remote', 'Remote two')
        ->assertSet('conflicts.name.original', 'Original');
});

it('accepts convergent edits and equivalent numeric values without conflicts', function (): void {
    $project = Project::factory()->create(['name' => 'Original', 'budget' => 1000]);
    $page = Livewire::test(EditProject::class, ['record' => $project->id])->fillForm(['name' => 'Agreed', 'budget' => '1000.0']);
    $project->update(['name' => 'Agreed', 'budget' => 2500]);

    $page->dispatch('echo-private:projects,ProjectChanged', ['projectId' => $project->id])
        ->assertSet('conflicts', [])->assertFormSet(['budget' => '2500.00']);
});

it('keeps a resolved local choice unless another remote change arrives', function (): void {
    $project = Project::factory()->create(['name' => 'Original']);
    $page = Livewire::test(EditProject::class, ['record' => $project->id])->fillForm(['name' => 'Local']);
    $project->update(['name' => 'Remote']);
    $page->call('save')->set('conflictChoices.name', 'local')->call('resolveConflicts');
    $project->update(['name' => 'New remote']);
    $page->call('save')->assertSet('conflicts.name.remote', 'New remote');
    expect($project->refresh()->name)->toBe('New remote');
});

it('compares plan content without builder keys and resolves a changed plan as one field', function (): void {
    $original = [['type' => 'milestone', 'data' => ['title' => 'Design', 'target_date' => '2026-11-01', 'description' => 'Initial scope']]];
    $remote = [['type' => 'milestone', 'data' => ['title' => 'Delivery', 'target_date' => '2026-12-01', 'description' => 'Expanded scope']]];
    $project = Project::factory()->create(['plan' => $original]);
    $page = Livewire::test(EditProject::class, ['record' => $project->id]);
    $project->update(['plan' => $remote]);

    $page->dispatch('echo-private:projects,ProjectChanged', ['projectId' => $project->id])
        ->assertSet('conflicts', []);
    expect(array_values($page->get('data.plan')))->toBe($remote);

    $page->set('data.plan', ['local-block' => $original[0]]);
    $remote[0]['data']['title'] = 'Release';
    $project->update(['plan' => $remote]);
    $page->call('save')->assertSet('conflicts.plan.remote', $remote)
        ->set('conflictChoices.plan', 'remote')->call('resolveConflicts')
        ->assertSet('conflicts', []);
    expect(array_values($page->get('data.plan')))->toBe($remote);
});

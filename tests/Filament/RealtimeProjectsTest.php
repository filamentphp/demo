<?php

use App\Enums\ProjectStatus;
use App\Events\ProjectChanged;
use App\Filament\Resources\HR\Projects\Pages\EditProject;
use App\Filament\Resources\HR\Projects\Pages\ListProjects;
use App\Filament\Resources\HR\Projects\Pages\ViewProject;
use App\Filament\Resources\HR\Projects\ProjectResource;
use App\Filament\Resources\HR\Projects\RelationManagers\TasksRelationManager;
use App\Livewire\ClientProjects;
use App\Models\HR\Project;
use App\Models\HR\Task;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;

it('saves client changes and broadcasts only the affected project id', function (): void {
    $project = Project::factory()->create(['name' => 'Original project']);
    Event::fake([ProjectChanged::class]);

    Livewire::test(ClientProjects::class, ['projectId' => $project->id])
        ->set('data.name', 'Client renamed project')
        ->set('data.status', 'on_hold')
        ->set('data.spent', '12345.67')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('message', 'Project saved.')
        ->assertDontSee('REALTIME DEMO')
        ->assertDontSee('Open live table')
        ->assertDontSee('Read-only updates only');

    expect($project->refresh())->name->toBe('Client renamed project')
        ->status->toBe(ProjectStatus::OnHold)
        ->spent->toBe('12345.67');
    Event::assertDispatched(ProjectChanged::class, fn (ProjectChanged $event): bool => $event->projectId === $project->id);
    Event::assertDispatchedTimes(ProjectChanged::class, 1);
    expect((new ProjectChanged($project->id))->broadcastOn()->name)->toBe('private-projects');
});

it('rejects invalid client changes without broadcasting', function (): void {
    $project = Project::factory()->create();
    Event::fake([ProjectChanged::class]);

    Livewire::test(ClientProjects::class, ['projectId' => $project->id])
        ->set('data.status', 'invalid')
        ->set('data.spent', -1)
        ->call('save')
        ->assertHasErrors(['data.status', 'data.spent']);

    Event::assertNotDispatched(ProjectChanged::class);
});

it('refreshes the record heading and infolist after an external change', function (): void {
    $project = Project::factory()->create(['name' => 'Before realtime', 'status' => ProjectStatus::Active]);
    $page = Livewire::test(ViewProject::class, ['record' => $project->id]);

    $project->update(['name' => 'After realtime', 'status' => ProjectStatus::OnHold, 'spent' => 9876.54]);

    $page->dispatch('echo-private:projects,ProjectChanged', ['projectId' => $project->id])
        ->assertSee('After realtime')
        ->assertSee('9,876.54')
        ->assertActionVisible('resume')
        ->assertActionHidden('put_on_hold');
});

it('preserves search filters sort and selection when a row leaves the table', function (): void {
    $project = Project::factory()->create(['name' => 'Realtime project', 'status' => ProjectStatus::Active]);
    $otherProject = Project::factory()->create(['name' => 'Realtime other', 'status' => ProjectStatus::Active]);
    $page = Livewire::test(ListProjects::class)
        ->searchTable('Realtime')
        ->filterTable('status', 'active')
        ->sortTable('name')
        ->selectTableRecords([$otherProject]);
    $selection = $page->get('selectedTableRecords');

    $project->update(['status' => ProjectStatus::OnHold]);

    $page->dispatch('echo-private:projects,ProjectChanged', ['projectId' => $project->id])
        ->assertCanNotSeeTableRecords([$project])
        ->assertCanSeeTableRecords([$otherProject])
        ->assertSet('tableSearch', 'Realtime')
        ->assertSet('tableFilters.status.value', 'active')
        ->assertSet('tableSort', 'name:asc')
        ->assertSet('selectedTableRecords', $selection);
});

it('adds a client task and refreshes the read-only relation table', function (): void {
    $project = Project::factory()->create();
    Event::fake([ProjectChanged::class]);
    $relation = Livewire::test(TasksRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => ViewProject::class,
    ]);

    Livewire::test(ClientProjects::class, ['projectId' => $project->id])
        ->set('taskTitle', 'Task from the client')
        ->call('addTask')
        ->assertHasNoErrors();

    $task = $project->tasks()->sole();
    expect($task->title)->toBe('Task from the client');
    Event::assertDispatched(ProjectChanged::class);
    Event::assertDispatchedTimes(ProjectChanged::class, 1);

    $relation->dispatch('echo-private:projects,ProjectChanged', ['projectId' => $project->id])
        ->assertCanSeeTableRecords([$task]);
});

it('does not change unsaved edit form data', function (): void {
    $project = Project::factory()->create(['name' => 'Original name']);
    $page = Livewire::test(EditProject::class, ['record' => $project->id])
        ->fillForm(['name' => 'Unsaved local draft']);

    $project->update(['name' => 'Remote name']);

    $page->dispatch('echo-private:projects,ProjectChanged', ['projectId' => $project->id])
        ->assertFormSet(['name' => 'Unsaved local draft'])
        ->assertSet('conflicts.name.remote', 'Remote name');
});

it('does not change a mounted action form during a broadcast', function (): void {
    $project = Project::factory()->create(['status' => ProjectStatus::Active]);
    $page = Livewire::test(ViewProject::class, ['record' => $project->id])
        ->mountAction('change_status')
        ->set('mountedActions.0.data.status', 'planning');

    $project->update(['status' => ProjectStatus::OnHold]);

    $page->dispatch('echo-private:projects,ProjectChanged', ['projectId' => $project->id])
        ->assertSet('mountedActions.0.name', 'change_status')
        ->assertSet('mountedActions.0.data.status', 'planning');
});

it('requires authentication for the client portal', function (): void {
    auth()->logout();

    $this->get(route('client.projects'))->assertRedirect(route('login'));
});

it('broadcasts project saves from the Filament edit page', function (): void {
    $project = Project::factory()->create();
    Event::fake([ProjectChanged::class]);

    Livewire::test(EditProject::class, ['record' => $project->id])
        ->fillForm(['name' => 'Saved in Filament'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($project->refresh()->name)->toBe('Saved in Filament');
    Event::assertDispatchedTimes(ProjectChanged::class, 1);
    Event::assertDispatched(ProjectChanged::class, fn (ProjectChanged $event): bool => $event->projectId === $project->id);
});

it('broadcasts project creation deletion and restoration', function (): void {
    Event::fake([ProjectChanged::class]);
    $project = Project::factory()->create();
    Event::assertDispatchedTimes(ProjectChanged::class, 1);

    $project->delete();
    Event::assertDispatchedTimes(ProjectChanged::class, 2);

    $project->restore();
    Event::assertDispatchedTimes(ProjectChanged::class, 3);
});

it('notifies both projects when a task moves and only its current project on subsequent saves', function (): void {
    $task = Task::factory()->for(Project::factory())->create();
    $oldProjectId = $task->project_id;
    $newProject = Project::factory()->create();
    Event::fake([ProjectChanged::class]);

    $task->update(['project_id' => $newProject->id]);

    Event::assertDispatchedTimes(ProjectChanged::class, 2);
    Event::assertDispatched(ProjectChanged::class, fn (ProjectChanged $event): bool => $event->projectId === $oldProjectId);
    Event::assertDispatched(ProjectChanged::class, fn (ProjectChanged $event): bool => $event->projectId === $newProject->id);

    Event::fake([ProjectChanged::class]);
    $task->save();
    Event::assertDispatchedTimes(ProjectChanged::class, 1);
    Event::assertDispatched(ProjectChanged::class, fn (ProjectChanged $event): bool => $event->projectId === $newProject->id);

    $task->delete();
    Event::assertDispatchedTimes(ProjectChanged::class, 2);
});

it('waits for a transaction to commit and discards rolled back changes', function (): void {
    $project = Project::factory()->create();
    $connection = $project->getConnection();
    Event::fake([ProjectChanged::class]);

    $connection->beginTransaction();
    $project->update(['name' => 'Uncommitted change']);
    Event::assertNotDispatched(ProjectChanged::class);
    $connection->rollBack();
    Event::assertNotDispatched(ProjectChanged::class);

    $connection->beginTransaction();
    $project->update(['name' => 'Committed change']);
    Event::assertNotDispatched(ProjectChanged::class);
    $connection->commit();
    Event::assertDispatchedTimes(ProjectChanged::class, 1);
});

it('leaves the read-only view when its project is deleted', function (): void {
    $project = Project::factory()->create();
    $page = Livewire::test(ViewProject::class, ['record' => $project->id]);
    $project->delete();

    $page->dispatch('echo-private:projects,ProjectChanged', ['projectId' => $project->id])
        ->assertRedirect(ProjectResource::getUrl('index'));
});

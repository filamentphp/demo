<?php

use App\Enums\ProjectStatus;
use App\Filament\Resources\HR\Projects\Pages\EditProject;
use App\Filament\Resources\HR\Projects\Pages\ViewProject;
use App\Livewire\ClientProjects;
use App\Livewire\ProjectHistory;
use App\Models\HR\Project;
use App\Models\HR\ProjectActivity;
use App\Models\User;
use Illuminate\Support\Facades\Context;
use Livewire\Livewire;

it('records only persisted field changes with panel attribution', function (): void {
    $project = Project::factory()->create(['name' => 'Original', 'status' => ProjectStatus::Active, 'budget' => 1234.56]);
    $created = $project->activities()->sole();
    expect($created->event)->toBe('project_created');

    Livewire::test(EditProject::class, ['record' => $project->id])
        ->fillForm(['name' => 'Updated', 'status' => 'on_hold', 'budget' => 9876.54])
        ->call('save')
        ->assertHasNoFormErrors();

    $activity = $project->activities()->latest('id')->first();
    expect($activity)->event->toBe('project_updated')
        ->user_id->toBe(auth()->id())
        ->actor_name->toBe(auth()->user()->name)
        ->interface->toBe('panel')
        ->and($activity->created_at)->not->toBeNull()
        ->and($activity->changes['name'])->toBe(['old' => 'Original', 'new' => 'Updated'])
        ->and($activity->changes['status'])->toBe(['old' => 'Active', 'new' => 'On hold'])
        ->and($activity->changes['budget'])->toBe(['old' => '$1,234.56', 'new' => '$9,876.54']);

    $project->refresh()->save();
    $project->update(['updated_at' => now()->addMinute()]);
    expect($project->activities()->count())->toBe(2);
});

it('attributes client writes and ignores task updates but preserves deleted task titles', function (): void {
    $project = Project::factory()->create();
    Livewire::test(ClientProjects::class, ['projectId' => $project->id])
        ->set('data.name', 'From client')
        ->call('save')
        ->set('taskTitle', 'Client task')
        ->call('addTask')
        ->assertHasNoErrors();

    $activities = $project->activities()->orderBy('id')->get();
    expect($activities->pluck('event')->all())->toBe(['project_created', 'project_updated', 'task_created'])
        ->and($activities[1]->interface)->toBe('client_portal')
        ->and($activities[2]->interface)->toBe('client_portal')
        ->and($activities[2]->user_id)->toBe(auth()->id());

    $task = $project->tasks()->sole();
    $task->update(['title' => 'Revised task title', 'status' => 'in_progress']);
    expect($project->activities()->count())->toBe(3);
    $task->delete();
    $deleted = $project->activities()->latest('id')->first();
    expect($deleted)->event->toBe('task_deleted')->subject->toBe('Revised task title')->interface->toBe('panel');
});

it('uses scoped authenticated attribution and summarizes rich content without raw state', function (): void {
    $project = Project::factory()->create();
    $actor = User::factory()->create(['name' => 'Portal collaborator']);

    Context::scope(
        fn () => $project->update(['description' => '<p>Secret HTML</p>', 'plan' => [['internal' => 'Plan data']]]),
        hidden: ['project_history_actor' => $actor, 'project_history_interface' => 'client_portal'],
    );
    $activity = $project->activities()->latest('id')->first();
    expect($activity)->user_id->toBe($actor->id)->actor_name->toBe('Portal collaborator')->interface->toBe('client_portal')
        ->changes->toBe(['description' => ['old' => null, 'new' => 'Updated'], 'plan' => ['old' => null, 'new' => 'Updated']]);

    $project->syncChanges();
    $project->setRawAttributes([...$project->getAttributes(), 'description_state' => 'CRDT payload']);
    $project->syncChanges();
    ProjectActivity::recordProjectUpdate($project);
    expect($project->activities()->count())->toBe(2);

    $project->refresh()->update(['name' => 'Panel again']);
    expect($project->activities()->latest('id')->first())->interface->toBe('panel')->user_id->toBe(auth()->id());
});

it('rolls back history with project writes and handles archive restore and permanent deletion', function (): void {
    $project = Project::factory()->create();
    $connection = $project->getConnection();
    $connection->beginTransaction();
    $project->update(['name' => 'Rolled back']);
    $connection->rollBack();
    expect($project->activities()->count())->toBe(1);

    $project->refresh()->delete();
    $project->restore();
    expect($project->activities()->orderBy('id')->pluck('event')->all())
        ->toBe(['project_created', 'project_deleted', 'project_restored']);
    $project->forceDelete();
    expect(ProjectActivity::query()->count())->toBe(0);
});

it('renders isolated newest-first history with escaped values and older activity pagination', function (): void {
    $project = Project::factory()->create();
    $other = Project::factory()->create();
    $other->update(['name' => 'Other project private activity']);
    for ($index = 1; $index <= 21; $index++) {
        $project->update(['name' => "Revision {$index}"]);
    }

    $history = Livewire::test(ProjectHistory::class, ['record' => $project])
        ->assertSeeHtml('class="w-full" aria-label="Project history"')
        ->assertSeeInOrder(['Revision 21', 'Revision 20'])
        ->assertDontSee('Project created')
        ->assertDontSee('Other project private activity')
        ->call('loadMore')
        ->assertSee('Project created');

    $project->update(['name' => '<script>alert(1)</script>']);
    $history->dispatch('echo-private:projects,ProjectChanged', ['projectId' => $other->id])
        ->assertDontSee('&lt;script&gt;', escape: false);
    $history->dispatch('echo-private:projects,ProjectChanged', ['projectId' => $project->id])
        ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', escape: false)
        ->assertDontSee('<script>alert(1)</script>', escape: false);

    Livewire::test(EditProject::class, ['record' => $project->id])->assertDontSee('History');
    Livewire::test(ViewProject::class, ['record' => $project->id])->assertDontSee('History');
});

it('records each explicit description save separately', function (): void {
    $this->travelTo(now()->startOfSecond());
    $project = Project::factory()->create();
    $project->update(['description' => 'First draft']);
    $first = $project->activities()->latest('id')->first();
    $this->travel(1)->seconds();
    $project->update(['description' => 'Second draft']);
    expect($project->activities()->count())->toBe(3)
        ->and($first->refresh()->updated_at->equalTo($first->created_at))->toBeTrue();
});

it('shows honest empty states and requires authentication', function (): void {
    Livewire::test(ProjectHistory::class)
        ->assertSee('Save this project to start its history.')
        ->assertDontSee('Live activity');
    $project = Project::withoutEvents(fn () => Project::factory()->create());
    Livewire::test(ProjectHistory::class, ['record' => $project])->assertSee('Earlier activity has not been backfilled.');

    auth()->logout();
    Livewire::test(ProjectHistory::class, ['record' => $project])->assertForbidden();
});

it('renders expandable field changes with status badges and summarized rich content', function (): void {
    $project = Project::factory()->create(['status' => ProjectStatus::Active]);
    $project->update(['status' => ProjectStatus::OnHold, 'description' => '<p>Private draft content</p>']);

    $html = Livewire::test(ProjectHistory::class, ['record' => $project])
        ->assertSee('Status')
        ->assertSee('Description')
        ->assertSee('Panel')
        ->assertDontSee('Private draft content')
        ->html();
    $document = new DOMDocument;
    @$document->loadHTML($html);
    $xpath = new DOMXPath($document);
    expect($xpath->query('//details[@open]')->length)->toBe(0)
        ->and($xpath->query('//details//dd//span[contains(concat(" ", normalize-space(@class), " "), " fi-badge ") and contains(., "On hold")]')->length)->toBe(1);

    $project->update(['name' => 'One field']);
    $html = Livewire::test(ProjectHistory::class, ['record' => $project])->html();
    @$document->loadHTML($html);
    expect((new DOMXPath($document))->query('//details[@open]')->length)->toBe(1);
});

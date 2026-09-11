<?php

use App\Filament\Resources\HR\Projects\Pages\EditProject;
use App\Forms\Components\ProjectCollaborationPlugin;
use App\Livewire\ClientProjects;
use App\Livewire\ProjectRevisions;
use App\Models\HR\Project;
use App\Models\HR\ProjectRevision;
use App\Models\HR\Task;
use App\Models\PageMessage;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;

it('keeps proposals durable and independent until a different user approves changed fields', function (): void {
    $author = auth()->user();
    $reviewer = User::factory()->create();
    $project = Project::factory()->create(['name' => 'Original', 'budget' => 100, 'description' => '<p>Old</p>']);

    $revision = ProjectRevision::propose($project, $author, [
        'name' => 'Proposed', 'description' => ['type' => 'doc', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Safe']]]]],
    ], 'Improve it', requestedReviewer: $reviewer);

    expect($project->fresh()->name)->toBe('Original')
        ->and($project->description)->toBe('<p>Old</p>')
        ->and($revision->base_values['description'])->toBe('<p>Old</p>')
        ->and($revision->proposed_values['description'])->toContain('Safe')
        ->and($revision->thread->parent_id)->toBeNull()
        ->and($revision->thread->is_agent)->toBeFalse();

    $this->actingAs($reviewer);
    $revision->approve($reviewer, 1);

    expect($project->fresh()->name)->toBe('Proposed')
        ->and($project->budget)->toBe('100.00')
        ->and($project->description_state)->toBeNull()
        ->and($revision->status)->toBe('applied')
        ->and($revision->open_project_id)->toBeNull()
        ->and(PageMessage::query()->where('parent_id', $revision->thread_id)->count())->toBe(1);
});

it('enforces one open revision and optimistic versions', function (): void {
    $author = auth()->user();
    $reviewer = User::factory()->create();
    $project = Project::factory()->create();
    $revision = ProjectRevision::propose($project, $author, ['budget' => 42], 'Budget', requestedReviewer: $reviewer);

    expect(fn () => ProjectRevision::propose($project, $author, ['name' => 'Other'], 'Other'))->toThrow(ValidationException::class);

    $this->actingAs($reviewer);
    $revision->requestChanges($reviewer, 1, 'Please explain');
    expect(fn () => $revision->requestChanges($reviewer, 1, 'Again'))->toThrow(ValidationException::class);

    $this->actingAs($author);
    $revision->revise($author, 2, ['budget' => 43], 'Explained');
    expect($revision->status)->toBe('pending')->and($revision->version)->toBe(3)->and($revision->feedback)->toBe('Please explain');
});

it('detects live stale values and requires explicit author resolution', function (): void {
    $author = auth()->user();
    $reviewer = User::factory()->create();
    $project = Project::factory()->create(['budget' => 100, 'name' => 'Original']);
    $revision = ProjectRevision::propose($project, $author, ['budget' => 200, 'name' => 'Proposed'], 'Update', requestedReviewer: $reviewer);

    $project->update(['budget' => 150]);
    expect($revision->staleFields())->toHaveKey('budget');

    $this->actingAs($reviewer);
    expect(fn () => $revision->approve($reviewer, 1))->toThrow(ValidationException::class);

    $this->actingAs($author);
    $revision->resolveStale($author, 1, ['budget' => 'current']);
    expect($revision->proposed_values)->not->toHaveKey('budget')->and($revision->base_values['budget'])->toBe('150.00');

    $this->actingAs($reviewer);
    $revision->approve($reviewer, 2);
    $project->refresh();
    expect($project->budget)->toBe('150.00')->and($project->name)->toBe('Proposed');
});

it('enforces authorship, reviewer separation, and effective changes', function (): void {
    $author = auth()->user();
    $other = User::factory()->create();
    $project = Project::factory()->create(['name' => 'Same']);

    expect(fn () => ProjectRevision::propose($project, $author, ['name' => 'Same'], 'No-op'))->toThrow(ValidationException::class);
    $revision = ProjectRevision::propose($project, $author, ['name' => 'Changed'], 'Useful', requestedReviewer: $other);
    expect(fn () => $revision->approve($author, 1))->toThrow(AuthorizationException::class)
        ->and(fn () => $revision->revise($other, 1, ['name' => 'Hijacked'], 'No'))->toThrow(AuthorizationException::class);

    $this->actingAs($other);
    $revision->reject($other, 1);
    expect($revision->status)->toBe('rejected')->and($revision->open_project_id)->toBeNull();
});

it('rejects a proposal opened before the client portal changed the project', function (): void {
    $project = Project::factory()->create(['name' => 'Original', 'start_date' => '2026-01-01', 'end_date' => null]);
    $page = Livewire::test(ProjectRevisions::class, ['record' => $project])->mountAction('propose');
    Livewire::test(ClientProjects::class, ['projectId' => $project->id])
        ->set('data.name', 'Client update')->call('save')->assertHasNoErrors();
    $page->fillForm(['name' => 'Proposal', 'reason' => 'A better name'])
        ->callMountedAction()->assertHasErrors(['revision']);
    expect(ProjectRevision::query()->count())->toBe(0)
        ->and($project->fresh()->name)->toBe('Client update');
});

it('creates and reviews a durable proposal through component actions', function (): void {
    $project = Project::factory()->create(['name' => 'Original', 'start_date' => '2026-01-01', 'end_date' => null]);
    Livewire::test(ProjectRevisions::class, ['record' => $project])
        ->mountAction('propose')->fillForm(['name' => 'Proposed', 'reason' => 'Clarify the scope'])
        ->callMountedAction()->assertHasNoErrors();
    $revision = ProjectRevision::query()->sole();
    expect($revision->proposed_values)->toBe(['name' => 'Proposed'])
        ->and($revision->requested_reviewer_id)->toBeNull();
    $reviewer = User::factory()->create();
    $revision->requestReview(auth()->user(), 1, $reviewer);
    $this->actingAs($reviewer);
    Livewire::test(ProjectRevisions::class, ['record' => $project])
        ->callAction(TestAction::make('approve')->arguments(['revision' => $revision->id, 'version' => 2]))
        ->assertHasNoErrors();
    expect($project->fresh()->name)->toBe('Proposed');
    expect($project->activities()->latest('id')->first())
        ->user_id->toBe(auth()->id())->subject->toContain('Clarify the scope');
});

it('does not apply an unseen revision version or a cross-project action', function (): void {
    $author = auth()->user();
    $project = Project::factory()->create();
    $reviewer = User::factory()->create();
    $revision = ProjectRevision::propose($project, $author, ['name' => 'First'], 'Reason', requestedReviewer: $reviewer);
    $this->actingAs($reviewer);
    $page = Livewire::test(ProjectRevisions::class, ['record' => $project])
        ->mountAction(TestAction::make('approve')->arguments(['revision' => $revision->id, 'version' => 1]));
    $this->actingAs($author);
    $revision->revise($author, 1, ['name' => 'Unseen'], 'Changed');
    $this->actingAs($reviewer);
    $page->callMountedAction()->assertHasErrors(['version']);
    expect($project->fresh()->name)->not->toBe('Unseen');
    expect(fn () => Livewire::test(ProjectRevisions::class, ['record' => Project::factory()->create()])
        ->call('openDiscussion', $revision->id))->toThrow(ModelNotFoundException::class);
});

it('refuses stale resolution if the current value changed again', function (): void {
    $project = Project::factory()->create(['name' => 'Baseline']);
    $revision = ProjectRevision::propose($project, auth()->user(), ['name' => 'Proposed'], 'Reason');
    $project->update(['name' => 'Current']);
    $project->update(['name' => 'New current']);
    expect(fn () => $revision->resolveStale(auth()->user(), 1, ['name' => 'proposed'], ['name' => 'Current']))
        ->toThrow(ValidationException::class);
    expect($revision->fresh()->version)->toBe(1);
});

it('protects approved descriptions from old editors even without receiving the broadcast', function (): void {
    $author = auth()->user();
    $project = Project::factory()->create(['description' => '<p>Original</p>', 'start_date' => '2026-01-01', 'end_date' => null]);
    $page = Livewire::test(EditProject::class, ['record' => $project->id])
        ->fillForm(['description' => '<p>Unsaved shared draft</p>', 'description_state' => 'b2xk']);
    $reviewer = User::factory()->create();
    $revision = ProjectRevision::propose($project, $author, ['description' => '<p>Approved replacement</p>'], 'Replace the brief', requestedReviewer: $reviewer);
    $this->actingAs($reviewer);
    $revision->approve(auth()->user(), 1);
    $this->actingAs($author);
    $page->call('save')->assertNotified('An approved revision replaced the description');
    expect($project->fresh()->description)->toContain('Approved replacement')
        ->and($project->fresh()->description_state)->toBeNull()
        ->and($project->fresh()->description_version)->toBe(1);
    expect(ProjectCollaborationPlugin::configuration($project->id)['document'])->toBe("project.{$project->id}.v1");
    $this->withToken(config('collaboration.key'))->getJson('/api/collaboration/projects/' . $project->id)
        ->assertJsonPath('version', 1)->assertJsonPath('state', null);
    Livewire::test(EditProject::class, ['record' => $project->id])
        ->fillForm(['description' => '<p>New session</p>'])->call('save')->assertHasNoFormErrors();
    expect($project->fresh()->description)->toContain('New session');
});

it('previews the same infolist and derived values without changing saved data', function (): void {
    $project = Project::factory()->create(['name' => 'Current name', 'budget' => 1000, 'spent' => 350]);
    $task = Task::factory()->for($project)->create(['title' => 'Current task', 'status' => 'todo']);
    $revision = ProjectRevision::propose($project, auth()->user(), ['name' => 'Proposed name', 'budget' => 1700], 'Preview impact', taskValues: [$task->id => ['title' => 'Proposed task', 'status' => 'completed']]);
    $page = Livewire::test(ProjectRevisions::class, ['record' => $project])
        ->mountAction(TestAction::make('preview')->arguments(['revision' => $revision->id]));
    $remainingBudget = fn () => collect($page->instance()->getSchema('previewInfolist')->getFlatComponents())
        ->first(fn ($component) => $component instanceof TextEntry && $component->getName() === 'remaining_budget')->getState();
    expect($remainingBudget())->toBe(1350.0)
        ->and($page->instance()->previewProject()->name)->toBe('Proposed name')
        ->and($page->instance()->previewProject()->tasks->first()->title)->toBe('Proposed task');
    $page->set('previewMode', 'current');
    expect($remainingBudget())->toBe(650.0)
        ->and($page->instance()->previewProject()->name)->toBe('Current name')
        ->and($project->fresh()->budget)->toBe('1000.00')
        ->and($task->fresh()->title)->toBe('Current task');
});

it('includes multiple task edits in the human proposal without persisting them', function (): void {
    $project = Project::factory()->create(['start_date' => '2026-01-01', 'end_date' => null]);
    $task = Task::factory()->for($project)->create(['title' => 'Before', 'status' => 'todo', 'due_date' => '2026-10-01']);
    Livewire::test(ProjectRevisions::class, ['record' => $project])->mountAction('propose')
        ->fillForm(['reason' => 'Move the review', 'tasks' => [['task_id' => $task->id, 'title' => 'After', 'status' => 'in_review', 'due_date' => '2026-10-09']]])
        ->callMountedAction()->assertHasNoErrors();
    expect(ProjectRevision::query()->sole()->proposed_tasks)->toBe([$task->id => ['title' => 'After', 'status' => 'in_review', 'due_date' => '2026-10-09']])
        ->and($task->fresh()->title)->toBe('Before');
});

<?php

use App\Enums\TaskStatus;
use App\Models\HR\Project;
use App\Models\HR\ProjectActivity;
use App\Models\HR\ProjectRevision;
use App\Models\HR\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

it('atomically applies project fields and task values on approval', function (): void {
    $author = auth()->user();
    $reviewer = User::factory()->create();
    $project = Project::factory()->create(['name' => 'Original', 'budget' => 100]);
    $task = Task::factory()->for($project)->create(['title' => 'Draft', 'status' => TaskStatus::Todo, 'due_date' => null]);
    $revision = ProjectRevision::propose($project, $author, ['name' => 'Approved'], 'Ship the coordinated update', taskValues: [
        $task->id => ['title' => 'Ready', 'status' => TaskStatus::InProgress->value, 'due_date' => '2026-10-01'],
    ], requestedReviewer: $reviewer);

    expect($project->fresh()->name)->toBe('Original')->and($task->fresh()->title)->toBe('Draft');
    $this->actingAs($reviewer);
    $revision->approve($reviewer, 1);

    expect($project->fresh()->name)->toBe('Approved')
        ->and($task->refresh()->title)->toBe('Ready')
        ->and($task->status)->toBe(TaskStatus::InProgress)
        ->and($task->due_date->format('Y-m-d'))->toBe('2026-10-01')
        ->and($revision->status)->toBe('applied');
});

it('supports a task-only proposal', function (): void {
    $project = Project::factory()->create(['name' => 'Unchanged']);
    $task = Task::factory()->for($project)->create(['title' => 'Before']);
    $reviewer = User::factory()->create();
    $revision = ProjectRevision::propose($project, auth()->user(), [], 'Refine task only', taskValues: [
        $task->id => ['title' => 'After'],
    ], requestedReviewer: $reviewer);
    expect($revision->proposed_values)->toBe([])->and($revision->proposed_tasks)->toBe([$task->id => ['title' => 'After']]);

    $this->actingAs($reviewer);
    $revision->approve($reviewer, 1);
    expect($project->fresh()->name)->toBe('Unchanged')->and($task->fresh()->title)->toBe('After');
});

it('rejects tasks belonging to another project', function (): void {
    $project = Project::factory()->create();
    $otherTask = Task::factory()->for(Project::factory())->create();

    expect(fn () => ProjectRevision::propose($project, auth()->user(), [], 'Invalid task', taskValues: [
        $otherTask->id => ['title' => 'Hijacked'],
    ]))->toThrow(ModelNotFoundException::class)
        ->and(ProjectRevision::query()->count())->toBe(0);
});

it('prevents every write when a proposed task is stale', function (): void {
    $project = Project::factory()->create(['name' => 'Original']);
    $task = Task::factory()->for($project)->create(['title' => 'Before']);
    $reviewer = User::factory()->create();
    $revision = ProjectRevision::propose($project, auth()->user(), ['name' => 'Proposed'], 'Atomic safety', taskValues: [
        $task->id => ['title' => 'Proposed task'],
    ], requestedReviewer: $reviewer);
    $task->update(['title' => 'Concurrent task edit']);

    $this->actingAs($reviewer);
    expect(fn () => $revision->approve($reviewer, 1))->toThrow(ValidationException::class)
        ->and($project->fresh()->name)->toBe('Original')
        ->and($task->fresh()->title)->toBe('Concurrent task edit')
        ->and($revision->fresh()->status)->toBe('pending');
});

it('removes a deleted task from a proposal when stale resolution keeps current', function (): void {
    $author = auth()->user();
    $project = Project::factory()->create(['name' => 'Original']);
    $task = Task::factory()->for($project)->create(['title' => 'Before']);
    $revision = ProjectRevision::propose($project, $author, ['name' => 'Still useful'], 'Handle deletion', taskValues: [
        $task->id => ['title' => 'No longer possible'],
    ]);
    $task->delete();

    $revision->resolveStale($author, 1, ["task_{$task->id}_title" => 'current']);
    expect($revision->proposed_tasks)->toBe([])->and($revision->proposed_values)->toBe(['name' => 'Still useful']);
});

it('creates a pending rollback that becomes stale after conflicting later changes and preserves unrelated fields', function (): void {
    $author = auth()->user();
    $project = Project::factory()->create(['name' => 'Original', 'budget' => 100, 'description' => '<p>Keep me</p>']);
    $task = Task::factory()->for($project)->create(['title' => 'Before']);
    $reviewer = User::factory()->create();
    $revision = ProjectRevision::propose($project, $author, ['name' => 'Applied'], 'Forward revision', taskValues: [$task->id => ['title' => 'After']], requestedReviewer: $reviewer);
    $this->actingAs($reviewer);
    $revision->approve($reviewer, 1);

    $this->actingAs($author);
    $rollback = $revision->proposeRollback($author, 'Undo the forward revision');
    expect($rollback->status)->toBe('pending')->and($rollback->rollback_of_id)->toBe($revision->id)
        ->and($rollback->proposed_values)->toBe(['name' => 'Original'])
        ->and($rollback->proposed_tasks)->toBe([$task->id => ['title' => 'Before']]);
    $rollback->requestReview($author, 1, $reviewer);
    $project->update(['name' => 'Later conflicting name', 'budget' => 777]);
    $task->update(['title' => 'Later conflicting task']);

    $this->actingAs($reviewer);
    expect(fn () => $rollback->approve($reviewer, 2))->toThrow(ValidationException::class)
        ->and($project->fresh()->name)->toBe('Later conflicting name')
        ->and($project->budget)->toBe('777.00')
        ->and($project->description)->toBe('<p>Keep me</p>')
        ->and($task->fresh()->title)->toBe('Later conflicting task');
});

it('groups an applied revision into one safe descriptive activity with complete changes', function (): void {
    $project = Project::factory()->create(['name' => 'Original']);
    $task = Task::factory()->for($project)->create(['title' => 'Before']);
    $reason = '<strong>' . str_repeat('Detailed safe reason ', 30) . '</strong>';
    $reviewer = User::factory()->create();
    $revision = ProjectRevision::propose($project, auth()->user(), ['name' => 'New'], $reason, taskValues: [
        $task->id => ['title' => 'After'],
    ], requestedReviewer: $reviewer);
    $this->actingAs($reviewer);
    $revision->approve($reviewer, 1);

    $activities = ProjectActivity::query()->where('project_id', $project->id)->where('event', 'revision_applied')->get();
    $activity = $activities->sole();
    expect($activity->revision_id)->toBe($revision->id)
        ->and($activity->changes['name'])->toBe(['old' => 'Original', 'new' => 'New'])
        ->and($activity->changes["Task #{$task->id} · title"])->toBe(['old' => 'Before', 'new' => 'After'])
        ->and($activity->subject)->toContain("Revision #{$revision->id}", 'Reason:')
        ->and($activity->subject)->not->toContain('<strong>')
        ->and(strlen($activity->subject))->toBeLessThanOrEqual(255)
        ->and($revision->fresh()->reason)->toBe($reason);
});

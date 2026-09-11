<?php

use App\Events\ProjectChanged;
use App\Models\HR\Project;
use App\Models\HR\ProjectActivity;
use App\Models\HR\ProjectRevision;
use App\Models\HR\Task;
use App\Models\User;
use Database\Seeders\RealtimeDemoSeeder;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'broadcasting.default' => 'null']);
    $this->artisan('migrate', ['--force' => true, '--no-interaction' => true])->assertSuccessful();
    $this->actingAs(User::factory()->create());
    $this->changes = collect();
    Event::listen(ProjectChanged::class, fn (ProjectChanged $event) => $this->changes->push($event->broadcastWith()));
});

it('rolls back and rebuilds the seeded collaboration schema including its circular foreign keys', function (): void {
    $tables = ['project_activities', 'page_messages', 'page_message_reads', 'project_revisions'];
    $schema = collect($tables)->mapWithKeys(fn (string $table): array => [$table => [
        Schema::getColumns($table), Schema::getIndexes($table), Schema::getForeignKeys($table),
    ]]);
    $this->seed(RealtimeDemoSeeder::class);
    $revision = ProjectRevision::query()->sole();
    $revision->project->activities()->first()->update(['revision_id' => $revision->id]);

    $this->artisan('migrate:rollback', ['--step' => 4, '--force' => true, '--no-interaction' => true])->assertSuccessful();
    foreach ($tables as $table) {
        expect(Schema::hasTable($table))->toBeFalse();
    }
    $this->artisan('migrate', ['--force' => true, '--no-interaction' => true])->assertSuccessful();
    foreach ($tables as $table) {
        expect([Schema::getColumns($table), Schema::getIndexes($table), Schema::getForeignKeys($table)])->toBe($schema[$table]);
    }
    $this->artisan('migrate:fresh', ['--seed' => true, '--seeder' => RealtimeDemoSeeder::class, '--force' => true, '--no-interaction' => true])->assertSuccessful();
    expect(Project::query()->count())->toBe(6)
        ->and(ProjectRevision::query()->sole()->requestedReviewer->email)->toBe('leo@northstar.example');
});

it('persists exactly the metadata envelope broadcast for a scoped rich content change', function (): void {
    $project = Project::factory()->create(['name' => 'Original']);
    $actor = User::factory()->create(['name' => 'Portal editor']);
    $this->changes = collect();
    $this->travelTo(now()->startOfSecond());

    Context::scope(fn () => $project->update([
        'name' => 'Private project name',
        'description' => '<p>Private HTML</p>',
        'description_state' => 'Private CRDT',
        'plan' => [['secret' => 'Private JSON']],
    ]), hidden: [
        'project_history_actor' => $actor,
        'project_history_interface' => 'client_portal',
        'project_history_reason' => 'explicit_save',
        'project_history_source' => 'project_form',
    ]);

    $activity = $project->activities()->latest('id')->first();
    $change = $activity->change_envelope;
    expect($this->changes->all())->toBe([['projectId' => $project->id, 'change' => $change]])
        ->and(Str::isUuid($change['id']))->toBeTrue()
        ->and($change)->toMatchArray([
            'schema_version' => 1, 'project_id' => $project->id, 'event' => 'project_updated',
            'actor' => ['id' => $actor->id, 'name' => 'Portal editor'],
            'interface' => 'client_portal', 'reason' => 'explicit_save', 'source' => 'project_form',
            'occurred_at' => now()->utc()->toISOString(),
            'entity' => ['type' => 'project', 'id' => $project->id],
            'fields' => ['name', 'description', 'plan'], 'revision_id' => null,
        ])
        ->and(json_encode($change))->not->toContain('Private', 'raw_changes', 'subject', 'email', 'password')
        ->and($activity->changes['description'])->toBe(['old' => null, 'new' => 'Updated'])
        ->and($activity->raw_changes)->toBe(['name' => ['old' => 'Original', 'new' => 'Private project name']]);

    $project->update(['name' => 'Panel change']);
    $next = $this->changes->last()['change'];
    expect($next['id'])->not->toBe($change['id'])
        ->and($next['actor']['id'])->toBe(auth()->id())
        ->and($next['interface'])->toBe('panel')
        ->and($next['reason'])->toBeNull()
        ->and($next['source'])->toBe('panel');

    $fallback = Context::scope(
        fn () => ProjectActivity::notify($project->id, 'project_changed'),
        hidden: ['project_history_actor' => null],
    );
    expect($fallback['actor']['id'])->toBe(auth()->id());

    auth()->logout();
    $system = ProjectActivity::notify($project->id, 'project_changed');
    expect($system['actor'])->toBe(['id' => null, 'name' => 'System'])
        ->and($system['interface'])->toBe('system');
});

it('broadcasts task create delete and updates once without adding update history and notifies both projects on moves', function (): void {
    $project = Project::factory()->create();
    $other = Project::factory()->create();
    $this->changes = collect();
    $task = Task::factory()->for($project)->create(['title' => 'Secret task']);
    expect($this->changes)->toHaveCount(1)
        ->and($this->changes->last()['change']['entity'])->toBe(['type' => 'task', 'id' => $task->id]);

    $task->update(['title' => 'Private updated title', 'labels' => ['Private label'], 'project_id' => $other->id]);
    expect($this->changes)->toHaveCount(3)
        ->and($this->changes->slice(1)->pluck('projectId')->all())->toBe([$other->id, $project->id])
        ->and($this->changes->last()['change']['fields'])->toEqualCanonicalizing(['title', 'labels', 'project_id'])
        ->and(ProjectActivity::query()->where('event', 'task_updated')->count())->toBe(0)
        ->and(json_encode($this->changes))->not->toContain('Private', 'Secret');

    $task->save();
    expect($this->changes)->toHaveCount(3);
    $task->delete();
    expect($this->changes)->toHaveCount(4)
        ->and($other->activities()->latest('id')->first()->event)->toBe('task_deleted');
});

it('ignores timestamp and no-op writes while state-only updates notify without history', function (): void {
    $project = Project::factory()->create();
    $this->changes = collect();
    $project->save();
    $project->update(['updated_at' => now()->addMinute()]);
    expect($this->changes)->toBeEmpty();
    $project->update(['description_state' => 'Hidden binary state']);
    expect($this->changes)->toHaveCount(1)
        ->and($this->changes->sole()['change']['fields'])->toBe(['description_state'])
        ->and($project->activities()->count())->toBe(1);
    $project->delete();
    $project->restore();
    expect($this->changes->pluck('change.event')->all())->toBe(['project_updated', 'project_deleted', 'project_restored']);
    $project->forceDelete();
    expect($this->changes)->toHaveCount(4)->and(ProjectActivity::query()->count())->toBe(0);
});

it('holds broadcasts until the outer commit and discards rolled back history and notifications', function (): void {
    $project = Project::factory()->create(['name' => 'Original']);
    $this->changes = collect();
    $connection = $project->getConnection();
    $connection->beginTransaction();
    $connection->beginTransaction();
    $project->update(['name' => 'Committed']);
    $envelope = $project->activities()->latest('id')->first()->change_envelope;
    $connection->commit();
    expect($this->changes)->toBeEmpty();
    $connection->commit();
    expect($this->changes->sole()['change'])->toBe($envelope);

    $connection->beginTransaction();
    $project->update(['name' => 'Rolled back']);
    ProjectActivity::notify($project->id, 'revision_review_requested', 'revision', 42, reason: 'review_requested');
    $connection->rollBack();
    expect($this->changes)->toHaveCount(1)
        ->and($project->refresh()->name)->toBe('Committed')
        ->and($project->activities()->count())->toBe(2);
});

it('groups revision writes into one explicit activity and offers history-free lifecycle notifications', function (): void {
    $project = Project::factory()->create();
    $revision = ProjectRevision::query()->create([
        'project_id' => $project->id, 'author_id' => auth()->id(), 'reason' => 'Private revision reason',
        'base_values' => [], 'proposed_values' => [],
    ]);
    $this->changes = collect();
    Context::scope(function () use ($project, $revision): void {
        $project->update(['name' => 'Grouped']);
        $task = Task::factory()->for($project)->create();
        $task->update(['title' => 'Grouped task']);
        $task->delete();
        expect($this->changes)->toBeEmpty();
        ProjectActivity::record($project->id, 'revision_applied', entityType: 'revision', entityId: $revision->id, fields: ['name', 'tasks']);
    }, hidden: ['project_history_revision_id' => $revision->id, 'project_history_revision_summary' => 'Private summary']);
    expect($this->changes)->toHaveCount(1)
        ->and($this->changes->sole()['change']['revision_id'])->toBe($revision->id)
        ->and($project->activities()->latest('id')->first()->subject)->toBe('Private summary')
        ->and(json_encode($this->changes))->not->toContain('Private');

    $change = ProjectActivity::notify($project->id, 'revision_review_requested', 'revision', $revision->id, ['requested_reviewer_id'], 'review_requested', 'revision_workflow');
    expect($this->changes->last()['change'])->toBe($change)
        ->and($change['revision_id'])->toBe($revision->id)
        ->and($change['reason'])->toBe('review_requested')
        ->and($project->activities()->count())->toBe(2);

    $scoped = Context::scope(
        fn () => ProjectActivity::notify($project->id, 'revision_changed', 'revision', 43),
        hidden: ['project_history_revision_id' => 79],
    );
    expect($scoped['revision_id'])->toBe(79);
});

it('records owner display names without allowing raw history restoration of ownership', function (): void {
    $originalOwner = User::factory()->create(['name' => 'Original owner']);
    $newOwner = User::factory()->create(['name' => 'New owner']);
    $project = Project::factory()->create(['owner_id' => $originalOwner->id]);
    expect($project->owner->is($originalOwner))->toBeTrue();
    $this->changes = collect();

    $project->update(['owner_id' => $newOwner->id]);
    $activity = $project->activities()->latest('id')->first();
    expect($activity->changes)->toBe(['owner_id' => ['old' => 'Original owner', 'new' => 'New owner']])
        ->and($activity->raw_changes)->toBeNull()
        ->and($this->changes->sole()['change']['fields'])->toBe(['owner_id'])
        ->and($project->refresh()->owner->is($newOwner))->toBeTrue();

    $project->update(['owner_id' => null]);
    expect($project->activities()->latest('id')->first()->changes)->toBe(['owner_id' => ['old' => 'New owner', 'new' => null]]);
});

it('keeps the legacy projectId contract and excludes extra model attributes from the broadcast', function (): void {
    $event = new ProjectChanged(73);
    $event->change['raw_changes'] = ['description' => '<p>Private</p>'];
    $event->change['actor']['email'] = 'private@example.com';
    $event->change['entity']['attributes'] = ['plan' => ['Private']];
    expect($event->broadcastWith()['projectId'])->toBe(73)
        ->and($event->broadcastWith()['change']['event'])->toBe('project_changed')
        ->and(json_encode($event->broadcastWith()))->not->toContain('Private', 'private@example.com', 'attributes', 'raw_changes')
        ->and($event->broadcastOn()->name)->toBe('private-projects');
});

it('publishes one committed change for an approved project and task revision', function (): void {
    $author = auth()->user();
    $reviewer = User::factory()->create(['name' => 'Reviewer']);
    $project = Project::factory()->create(['budget' => 100, 'description' => '<p>Before</p>']);
    $task = Task::factory()->for($project)->create(['title' => 'Before task']);
    $revision = ProjectRevision::propose(
        $project,
        $author,
        ['budget' => 275, 'description' => '<p>Private new body</p>'],
        'Private reason',
        taskValues: [$task->id => ['title' => 'Private task title']],
        requestedReviewer: $reviewer
    );
    $this->changes = collect();
    $this->actingAs($reviewer);
    $revision->approve($reviewer, 1);
    $activity = $project->activities()->where('event', 'revision_applied')->sole();

    expect($this->changes)->toHaveCount(1)
        ->and($this->changes->sole()['change'])->toBe($activity->change_envelope)
        ->and($activity->change_envelope['fields'])->toBe(['budget', 'description', "task_{$task->id}_title"])
        ->and($activity->change_envelope['actor'])->toBe(['id' => $reviewer->id, 'name' => 'Reviewer'])
        ->and($activity->change_envelope['reason'])->toBe('revision_approved')
        ->and($activity->revision_id)->toBe($revision->id)
        ->and(json_encode($this->changes))->not->toContain('Private')
        ->and($revision->status)->toBe('applied')
        ->and($project->fresh()->budget)->toBe('275.00')
        ->and($task->fresh()->title)->toBe('Private task title');
});

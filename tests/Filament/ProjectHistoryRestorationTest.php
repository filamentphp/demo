<?php

use App\Enums\ProjectStatus;
use App\Livewire\ProjectHistory;
use App\Models\HR\Project;
use App\Models\HR\ProjectActivity;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('restores a scalar value as a newly attributed project update', function (): void {
    $actor = auth()->user();
    $actorId = $actor->id;
    $actorName = $actor->name;
    $project = Project::factory()->create(['name' => 'Original name']);
    $project->update(['name' => 'Current name']);
    $activity = $project->activities()->latest('id')->firstOrFail();

    expect($activity->raw_changes)->toBe([
        'name' => ['old' => 'Original name', 'new' => 'Current name'],
    ]);

    Livewire::test(ProjectHistory::class, ['record' => $project])
        ->callAction(TestAction::make('restoreField'), arguments: [
            'activity' => $activity->id,
            'field' => 'name',
        ])
        ->assertNotified('Project value restored');

    expect($project->refresh()->name)->toBe('Original name');
    $restoration = $project->activities()->latest('id')->firstOrFail();
    expect($restoration->event)->toBe('project_updated')
        ->and($restoration->user_id)->toBe($actorId)
        ->and($restoration->actor_name)->toBe($actorName)
        ->and($restoration->interface)->toBe('panel')
        ->and($restoration->raw_changes['name'])->toBe([
            'old' => 'Current name',
            'new' => 'Original name',
        ]);
});

it('rejects repeated restoration and intervening changes', function (): void {
    $project = Project::factory()->create(['status' => ProjectStatus::Planning]);
    $project->update(['status' => ProjectStatus::Active]);
    $activity = $project->activities()->latest('id')->firstOrFail();
    $action = TestAction::make('restoreField');
    $arguments = ['activity' => $activity->id, 'field' => 'status'];

    Livewire::test(ProjectHistory::class, ['record' => $project])
        ->callAction($action, arguments: $arguments)
        ->callAction($action, arguments: $arguments)
        ->assertNotified('Could not restore value');
    expect($project->refresh()->status)->toBe(ProjectStatus::Planning);

    $project->update(['status' => ProjectStatus::Completed]);
    Livewire::test(ProjectHistory::class, ['record' => $project])
        ->callAction($action, arguments: $arguments)
        ->assertNotified('Could not restore value');
    expect($project->refresh()->status)->toBe(ProjectStatus::Completed);
});

it('rejects activity ids belonging to another project', function (): void {
    $project = Project::factory()->create(['name' => 'First']);
    $other = Project::factory()->create(['name' => 'Other old']);
    $other->update(['name' => 'Other new']);
    $activity = $other->activities()->latest('id')->firstOrFail();

    $rejected = false;

    try {
        Livewire::test(ProjectHistory::class, ['record' => $project])
            ->callAction(TestAction::make('restoreField'), arguments: [
                'activity' => $activity->id,
                'field' => 'name',
            ]);
    } catch (Throwable) {
        $rejected = true;
    }

    expect($rejected)->toBeTrue()
        ->and($project->refresh()->name)->toBe('First');
});

it('keeps historical and summarized changes non-restorable', function (): void {
    $project = Project::factory()->create();
    $project->update(['description' => '<p>Summary only</p>', 'spent' => 50, 'actual_hours' => 2]);
    $summarized = $project->activities()->latest('id')->firstOrFail();
    ProjectActivity::query()->create([
        'project_id' => $project->id,
        'actor_name' => 'Historical user',
        'interface' => 'panel',
        'event' => 'project_updated',
        'changes' => ['name' => ['old' => 'Past', 'new' => 'Present']],
        'raw_changes' => null,
    ]);

    expect($summarized->raw_changes)->toBeNull();
    Livewire::test(ProjectHistory::class, ['record' => $project])
        ->assertSee('Description')
        ->assertSee('Historical user')
        ->assertDontSee('Restore');
});

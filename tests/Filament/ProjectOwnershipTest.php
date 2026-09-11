<?php

use App\Filament\Resources\HR\Projects\Pages\ProjectAttention;
use App\Livewire\ProjectRevisions;
use App\Models\HR\Project;
use App\Models\HR\ProjectRevision;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;

it('hands a review to a named person and invalidates the previous reviewer modal', function (): void {
    $author = auth()->user();
    $reviewer = User::factory()->create(['name' => 'Leo']);
    $replacement = User::factory()->create(['name' => 'Ava']);
    $project = Project::factory()->create(['budget' => 100]);
    $revision = ProjectRevision::propose($project, $author, ['budget' => 250], 'More scope');
    expect($revision->nextStep())->toBe('Choose a reviewer')->and($revision->nextStepOwner()->id)->toBe($author->id);
    $this->actingAs($reviewer);
    expect(fn () => $revision->approve($reviewer, 1))->toThrow(AuthorizationException::class);
    $this->actingAs($author);
    $revision->requestReview($author, 1, $reviewer);
    expect($revision->nextStepOwner()->id)->toBe($reviewer->id)->and($revision->review_requested_at)->not->toBeNull();

    $this->actingAs($reviewer);
    $page = Livewire::test(ProjectRevisions::class, ['record' => $project])
        ->mountAction(TestAction::make('approve')->arguments(['revision' => $revision->id, 'version' => 2]));
    $revision->requestReview($reviewer, 2, $replacement);
    $page->callMountedAction()->assertHasErrors(['version']);
    expect(fn () => $revision->approve($reviewer, 3))->toThrow(AuthorizationException::class);
    expect($revision->nextStepOwner()->id)->toBe($replacement->id)
        ->and($project->fresh()->budget)->toBe('100.00')
        ->and($revision->thread->replies()->latest('id')->first()->body)->toContain('from Leo to Ava');

    $this->actingAs($replacement);
    $revision->approve($replacement, 3);
    expect($revision->nextStepOwner())->toBeNull()->and($project->fresh()->budget)->toBe('250.00');
});

it('returns the next step to the author for feedback and conflicts without forgetting the reviewer', function (): void {
    $author = auth()->user();
    $reviewer = User::factory()->create();
    $project = Project::factory()->create(['budget' => 100]);
    $revision = ProjectRevision::propose($project, $author, ['budget' => 200], 'More scope', requestedReviewer: $reviewer);
    $this->actingAs($reviewer);
    $revision->requestChanges($reviewer, 1, 'Please reduce the increase');
    expect($revision->nextStepOwner()->id)->toBe($author->id)->and($revision->nextStep())->toBe('Revise and resubmit');
    $this->actingAs($author);
    $revision->revise($author, 2, ['budget' => 175], 'Reduced scope');
    expect($revision->nextStepOwner()->id)->toBe($reviewer->id);
    $project->update(['budget' => 120]);
    expect($revision->nextStepOwner()->id)->toBe($author->id)->and($revision->nextStep())->toBe('Resolve changed values');
    $this->actingAs($reviewer);
    expect(Livewire::test(ProjectAttention::class)->instance()->revisionsAwaitingReview())->toHaveCount(0);
    $this->actingAs($author);
    expect(Livewire::test(ProjectAttention::class)->instance()->ownRevisionsNeedingAttention()->modelKeys())->toBe([$revision->id]);
    $revision->resolveStale($author, 3, ['budget' => 'proposed']);
    expect($revision->nextStepOwner()->id)->toBe($reviewer->id);
});

it('rejects self review requests and review handoffs by unrelated users', function (): void {
    $author = auth()->user();
    $other = User::factory()->create();
    $reviewer = User::factory()->create();
    $project = Project::factory()->create();
    $revision = ProjectRevision::propose($project, $author, ['name' => 'A new name'], 'Clarity');
    expect(fn () => $revision->requestReview($author, 1, $author))->toThrow(ValidationException::class);
    $this->actingAs($other);
    expect(fn () => $revision->requestReview($other, 1, $reviewer))->toThrow(AuthorizationException::class);
    expect($revision->fresh()->version)->toBe(1)->and($revision->requested_reviewer_id)->toBeNull();
});

it('assigns project ownership independently and refuses a stale assignment modal', function (): void {
    $owner = User::factory()->create(['name' => 'Maya']);
    $other = User::factory()->create(['name' => 'Leo']);
    $project = Project::factory()->create(['owner_id' => null]);
    $page = Livewire::test(ProjectRevisions::class, ['record' => $project])
        ->mountAction('assignOwner')->fillForm(['owner_id' => $owner->id]);
    $project->update(['owner_id' => $other->id]);
    $page->callMountedAction()->assertHasErrors(['owner_id']);
    expect($project->fresh()->owner_id)->toBe($other->id);
    Livewire::test(ProjectRevisions::class, ['record' => $project])
        ->mountAction('assignOwner')->fillForm(['owner_id' => $owner->id])->callMountedAction()->assertHasNoErrors()->assertSee('Maya');
    $activity = $project->activities()->latest('id')->first();
    expect($project->fresh()->owner_id)->toBe($owner->id)
        ->and($activity->changes['owner_id'])->toBe(['old' => 'Leo', 'new' => 'Maya'])
        ->and($activity->change_envelope['source'])->toBe('ownership');
});

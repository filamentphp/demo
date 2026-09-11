<?php

use App\Livewire\ProjectRevisions;
use App\Models\HR\Project;
use App\Models\HR\ProjectRevision;
use App\Models\User;
use Dom\Element;
use Dom\HTMLDocument;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('presents one compact decision with role appropriate buttons and a keyboard dropdown', function (string $role): void {
    $author = auth()->user();
    $author->update(['name' => 'Maya Chen']);
    $reviewer = User::factory()->create(['name' => 'Leo Martinez']);
    $project = Project::factory()->create(['budget' => 1250]);
    $revision = ProjectRevision::propose($project, $author, ['budget' => 1800], 'Cover the added delivery work.', requestedReviewer: $reviewer);
    $this->actingAs(match ($role) {
        'author' => $author,
        'reviewer' => $reviewer,
        default => User::factory()->create(),
    });

    $page = Livewire::test(ProjectRevisions::class, ['record' => $project])
        ->assertSee('Budget proposal')
        ->assertSee($role === 'reviewer' ? 'Awaiting your review' : 'Awaiting Leo Martinez’s review')
        ->assertDontSee('Project owner')->assertDontSee('Requested reviewer:')
        ->assertDontSee('Review requested from')->assertDontSee('Next step')
        ->assertDontSee('One proposal is already open')->assertDontSee('Version ')
        ->assertDontSee('Revision #');
    $decision = HTMLDocument::createFromString($page->html(), LIBXML_NOERROR)->querySelector('#revision-' . $revision->id);
    expect($decision->hasAttribute('open'))->toBeTrue()
        ->and(substr_count($decision->textContent, 'Maya Chen'))->toBe(1)
        ->and(substr_count($decision->textContent, 'Leo Martinez'))->toBe($role === 'reviewer' ? 0 : 1);
    $diff = collect($decision->querySelectorAll('p'))->first(fn (Element $node): bool => str_contains($node->textContent, '$1,250.00'))->textContent;
    expect(str($diff)->squish()->toString())->toBe('Budget Before: $1,250.00 → Proposed: $1,800.00');

    $buttons = collect($decision->querySelectorAll('button'))->map(fn (Element $node): string => trim($node->textContent))->all();
    expect($buttons)->toContain('Preview', 'More', 'Open discussion');
    if ($role === 'author') {
        expect($buttons)->toContain('Edit proposal', 'Withdraw', 'Change reviewer')->not->toContain('Approve & apply', 'Request changes', 'Reject');
    } elseif ($role === 'reviewer') {
        expect($buttons)->toContain('Approve & apply', 'Request changes', 'Reject', 'Change reviewer')->not->toContain('Edit proposal', 'Withdraw');
    } else {
        expect($buttons)->not->toContain('Approve & apply', 'Request changes', 'Reject', 'Edit proposal', 'Withdraw', 'Change reviewer');
    }
    expect($decision->querySelectorAll('button[aria-label="More proposal actions"]')->length)->toBe(1)
        ->and($decision->querySelector('.fi-dropdown-trigger')->getAttribute('x-on:keyup.enter'))->toBe('toggle($event)')
        ->and($decision->querySelector('.fi-dropdown-trigger')->getAttribute('x-on:keyup.space'))->toBe('toggle($event)')
        ->and(collect($decision->querySelectorAll('.fi-dropdown-list button'))->map(fn (Element $node): string => trim($node->textContent))->all())->toContain('Preview');
    $page->call('openDiscussion', $revision->id)
        ->assertDispatched('open-page-chat', room: hash('sha256', '/projects/' . $project->id), threadId: $revision->thread_id);
})->with(['author', 'reviewer', 'other']);

it('lets the author request a reviewer from More without duplicating reviewer details', function (): void {
    $author = auth()->user();
    $reviewer = User::factory()->create(['name' => 'Leo Martinez']);
    $project = Project::factory()->create(['budget' => 100]);
    $revision = ProjectRevision::propose($project, $author, ['budget' => 200], 'New budget');
    $page = Livewire::test(ProjectRevisions::class, ['record' => $project])->assertSee('Choose a reviewer');
    $dom = HTMLDocument::createFromString($page->html(), LIBXML_NOERROR);
    expect($dom->querySelector('.fi-dropdown-list')->textContent)->toContain('Request review');
    $page->callAction(TestAction::make('requestReview')->arguments(['revision' => $revision->id, 'version' => 1]), [
        'requested_reviewer_id' => $reviewer->id,
    ])->assertHasNoErrors()->assertSee('Awaiting Leo Martinez’s review');
    expect($revision->fresh()->requested_reviewer_id)->toBe($reviewer->id)
        ->and($revision->fresh()->version)->toBe(2);
});

it('derives titles from the actual changed fields', function (array $values, string $title): void {
    $project = Project::factory()->create(['budget' => 100, 'end_date' => '2026-10-01']);
    ProjectRevision::propose($project, auth()->user(), $values, 'Updated plan');
    Livewire::test(ProjectRevisions::class, ['record' => $project])->assertSee($title);
})->with([
    'delivery date' => [['end_date' => '2026-10-15'], 'Delivery-date proposal'],
    'mixed' => [['end_date' => '2026-10-15', 'budget' => 300], 'Project proposal'],
]);

it('prioritizes conflict resolution and never offers approval for stale decisions', function (): void {
    $author = auth()->user();
    $reviewer = User::factory()->create();
    $project = Project::factory()->create(['budget' => 100]);
    $revision = ProjectRevision::propose($project, $author, ['budget' => 200], 'New budget', requestedReviewer: $reviewer);
    $project->update(['budget' => 150]);
    $page = Livewire::test(ProjectRevisions::class, ['record' => $project])
        ->assertSee('Resolve conflicts before review')->assertSee('Budget conflict');
    $dom = HTMLDocument::createFromString($page->html(), LIBXML_NOERROR);
    expect($dom->querySelector('[role="alert"]')->textContent)->toContain('Project changed.')
        ->and($dom->querySelector('footer > div > button')->textContent)->toContain('Resolve conflicts');

    $this->actingAs($reviewer);
    Livewire::test(ProjectRevisions::class, ['record' => $project])
        ->assertSee('Awaiting ' . $author->name . '’s conflict resolution')->assertDontSee('Approve &amp; apply', false)
        ->callAction(TestAction::make('approve')->arguments(['revision' => $revision->id, 'version' => 1]))
        ->assertHasErrors(['revision']);
    expect($project->fresh()->budget)->toBe('150.00');
});

it('shows feedback and the author resubmission action instead of approval', function (): void {
    $author = auth()->user();
    $reviewer = User::factory()->create();
    $project = Project::factory()->create(['budget' => 100]);
    $revision = ProjectRevision::propose($project, $author, ['budget' => 200], 'New budget', requestedReviewer: $reviewer);
    $this->actingAs($reviewer);
    $revision->requestChanges($reviewer, 1, 'Explain the additional cost.');
    Livewire::test(ProjectRevisions::class, ['record' => $project])
        ->assertSee('Awaiting ' . $author->name . '’s resubmission')->assertDontSee('Approve &amp; apply', false);
    $this->actingAs($author);
    $page = Livewire::test(ProjectRevisions::class, ['record' => $project])->assertSee('Explain the additional cost.');
    expect(HTMLDocument::createFromString($page->html(), LIBXML_NOERROR)->querySelector('footer > div > button')->textContent)->toContain('Edit & resubmit');
});

it('keeps closed decisions collapsed with rollback and safe expandable description comparisons', function (): void {
    $author = auth()->user();
    $reviewer = User::factory()->create();
    $project = Project::factory()->create(['description' => '<p>Original brief</p>']);
    $revision = ProjectRevision::propose($project, $author, ['description' => '<p><strong>Updated brief</strong></p><script>alert(1)</script>'], 'Clarify the brief', requestedReviewer: $reviewer);
    $this->actingAs($reviewer);
    $revision->approve($reviewer, 1);
    $page = Livewire::test(ProjectRevisions::class, ['record' => $project])->assertSee('Applied')->assertSee('Propose rollback');
    $decision = HTMLDocument::createFromString($page->html(), LIBXML_NOERROR)->querySelector('#revision-' . $revision->id);
    expect($decision->hasAttribute('open'))->toBeFalse()
        ->and($decision->querySelector('details')->querySelector('summary')->textContent)->toBe('Description comparison')
        ->and($decision->querySelector('details')->hasAttribute('open'))->toBeFalse()
        ->and($decision->querySelector('.fi-prose')->textContent)->toBe('Original brief')
        ->and($decision->querySelector('.fi-prose strong')->textContent)->toBe('Updated brief')
        ->and($decision->querySelectorAll('.fi-prose script')->length)->toBe(0)
        ->and($decision->querySelector('.fi-dropdown-list')->textContent)->toContain('Propose rollback');

    $openRevision = ProjectRevision::propose($project->fresh(), $reviewer, ['name' => 'Next project name'], 'Clarify the name');
    $page->call('$refresh')->assertSee('Applied')->assertSee('Project-name proposal')->assertDontSee('Propose rollback');
    $dom = HTMLDocument::createFromString($page->html(), LIBXML_NOERROR);
    expect($dom->querySelector('#revision-' . $revision->id)->hasAttribute('open'))->toBeFalse()
        ->and($dom->querySelector('#revision-' . $openRevision->id)->hasAttribute('open'))->toBeTrue();
});

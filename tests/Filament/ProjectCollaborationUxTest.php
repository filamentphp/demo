<?php

use App\Ai\PageInteraction;
use App\Filament\Resources\HR\Projects\Pages\EditProject;
use App\Filament\Resources\HR\Projects\Pages\ViewProject;
use App\Livewire\PageChat;
use App\Models\HR\Project;
use App\Models\HR\ProjectRevision;
use App\Models\PageMessage;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;

it('keeps agent proposals durable and requires a different user to approve them', function (): void {
    $project = Project::factory()->create(['name' => 'Original', 'budget' => 1200]);
    $component = Livewire::test(EditProject::class, ['record' => $project->id]);
    $request = PageMessage::query()->create(['room' => hash('sha256', '/projects/' . $project->id), 'user_id' => auth()->id(), 'body' => 'Suggest changes']);
    $page = new PageInteraction($component->instance(), $request);
    $page->operate('propose_revision', ['values' => ['name' => 'Suggested', 'budget' => 2400], 'reason' => 'Improve plan']);
    $revision = ProjectRevision::query()->sole();
    expect(fn () => $revision->approve(auth()->user(), 1))->toThrow(AuthorizationException::class)
        ->and($project->refresh()->name)->toBe('Original');
    $reviewer = User::factory()->create();
    $revision->requestReview(auth()->user(), 1, $reviewer);
    $this->actingAs($reviewer);
    $revision->approve($reviewer, 2);
    expect($project->refresh()->name)->toBe('Suggested')->and($project->budget)->toBe('2400.00');
});

it('rejects a stale durable proposal without overwriting a remote value', function (): void {
    $project = Project::factory()->create(['name' => 'Original']);
    $reviewer = User::factory()->create();
    $revision = ProjectRevision::propose($project, auth()->user(), ['name' => 'Suggested'], 'Improve name', requestedReviewer: $reviewer);
    $project->update(['name' => 'Remote value']);
    $this->actingAs($reviewer);
    expect(fn () => $revision->approve($reviewer, 1))->toThrow(ValidationException::class)
        ->and($project->refresh()->name)->toBe('Remote value');
});

it('keeps description discussions and preserves a message already being composed', function (): void {
    $project = Project::factory()->create();
    $chat = Livewire::test(PageChat::class, ['page' => '/projects/' . $project->id])
        ->call('discussProjectField', $project->id, 'description')->assertSet('projectField', 'description')
        ->set('body', '<p>Can we approve this increase?</p>')
        ->call('discussProjectField', $project->id, 'description')->assertSet('projectField', 'description')
        ->call('send');
    expect(PageMessage::query()->sole())->project_field->toBe('description')->body->toContain('Can we approve this increase?');
    $chat->assertSet('projectField', null);
    Livewire::test(PageChat::class, ['page' => '/products'])
        ->call('discussProjectField', $project->id, 'budget')->assertSet('projectField', null)
        ->assertDontSee('Catch me up')->call('catchUpProject')->assertForbidden();
});

it('removes simple-field discussion controls and rejects new simple-field threads', function (): void {
    $project = Project::factory()->create();
    foreach ([EditProject::class, ViewProject::class] as $page) {
        $component = Livewire::test($page, ['record' => $project->id])
            ->assertSeeHtml('data-project-field-link="description"');
        foreach (['name', 'status', 'priority', 'end_date', 'budget'] as $field) {
            $component->assertDontSeeHtml('data-project-field-link="' . $field . '"');
        }
    }
    Livewire::test(PageChat::class, ['page' => '/projects/' . $project->id])
        ->call('discussProjectField', $project->id, 'budget')->assertStatus(422);
    $message = PageMessage::query()->create(['room' => hash('sha256', '/projects/' . $project->id), 'user_id' => auth()->id(), 'body' => 'Earlier budget discussion', 'project_field' => 'budget']);
    Livewire::test(PageChat::class, ['page' => '/projects/' . $project->id])
        ->call('openThread', $message->id)->assertSee('About budget')->assertSee('Earlier budget discussion');
});

it('prepares a read-only catch-up request from the previous visit', function (): void {
    $project = Project::factory()->create();
    $since = now()->subDays(3)->toIso8601String();
    Cache::put('project-visit:' . auth()->id() . ':' . $project->id, $since);
    Livewire::test(PageChat::class, ['page' => '/projects/' . $project->id])
        ->call('catchUpProject')->assertDispatched('page-agent-request');
    $request = PageMessage::query()->where('is_agent', false)->sole();
    expect($request->mentionsAgent())->toBeTrue()
        ->and($request->body)->toContain($since, 'Do not change any data');
});

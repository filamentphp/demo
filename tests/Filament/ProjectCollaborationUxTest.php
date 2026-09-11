<?php

use App\Ai\PageInteraction;
use App\Filament\Resources\HR\Projects\Pages\EditProject;
use App\Filament\Resources\HR\Projects\Pages\ViewProject;
use App\Livewire\PageChat;
use App\Models\HR\Project;
use App\Models\PageMessage;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

it('rejects stale proposals and does not let the agent accept its own proposal', function (): void {
    $project = Project::factory()->create(['name' => 'Original', 'budget' => 1200]);
    $component = Livewire::test(EditProject::class, ['record' => $project->id]);
    $page = new PageInteraction($component->instance(), new PageMessage);
    $page->operate('update_form', ['data.name' => 'Suggested', 'data.budget' => 2400]);
    expect($page->operate('save_form', []))->toContain('Not saved')
        ->and($page->inspect()['header_actions'])->not->toContain('reviewAgentProposal');
    $page->page->data['name'] = 'New local draft';
    $page->page->callMountedAction();
    expect($page->page->data['name'])->toBe('New local draft')
        ->and((float) $page->page->data['budget'])->toBe(1200.0)
        ->and($project->refresh()->name)->toBe('Original');
});

it('rejects a proposal after a remote save without overwriting that value', function (): void {
    $project = Project::factory()->create(['name' => 'Original']);
    $component = Livewire::test(EditProject::class, ['record' => $project->id]);
    $page = new PageInteraction($component->instance(), new PageMessage);
    $page->operate('update_form', ['data.name' => 'Suggested']);
    $project->update(['name' => 'Remote value']);
    $page->page->callMountedAction();
    expect($page->page->data['name'])->toBe('Remote value')
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

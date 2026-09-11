<?php

use App\Ai\Agents\PageAssistant;
use App\Ai\PageInteraction;
use App\Enums\ProjectStatus;
use App\Events\PageAgentStreamed;
use App\Filament\Resources\HR\Projects\Pages\EditProject;
use App\Filament\Resources\HR\Projects\Pages\ListProjects;
use App\Filament\Resources\HR\Projects\Pages\ViewProject;
use App\Livewire\PageChat;
use App\Models\HR\Project;
use App\Models\PageMessage;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Laravel\Ai\Responses\Data\ToolCall;
use Livewire\Livewire;

it('inspects and updates only editable form drafts without saving', function (): void {
    $project = Project::factory()->create(['name' => 'Original']);
    $component = Livewire::test(EditProject::class, ['record' => $project->id]);
    $page = new PageInteraction($component->instance(), new PageMessage);
    $state = $page->inspect();
    expect($state['page_type'])->toBe('edit')
        ->and($state['form']['data.name']['value'])->toBe('Original')
        ->and($state['form'])->not->toHaveKey('data.description_state')
        ->and($state['form']['data.slug']['editable'])->toBeFalse();
    $page->operate('update_form', ['data.name' => 'Agent draft', 'data.budget' => 9234]);
    expect($component->instance()->data['name'])->toBe('Original');
    $page->page->callMountedAction();
    expect($component->instance()->data['name'])->toBe('Agent draft')
        ->and($project->refresh()->name)->toBe('Original');
    expect(fn () => $page->operate('update_form', ['data.slug' => 'forbidden']))->toThrow(ValidationException::class);
});

it('uses native form validation and does not save unresolved concurrent changes', function (): void {
    $project = Project::factory()->create(['name' => 'Original']);
    $page = new PageInteraction(Livewire::test(EditProject::class, ['record' => $project->id])->instance(), new PageMessage);
    $page->operate('update_form', ['data.name' => null]);
    $page->page->callMountedAction();
    expect(fn () => $page->operate('save_form', []))->toThrow(ValidationException::class);
    $page->operate('update_form', ['data.name' => 'Local draft']);
    $page->page->callMountedAction();
    $project->update(['name' => 'Remote edit']);
    expect($page->operate('save_form', []))->toContain('Not saved')
        ->and($project->refresh()->name)->toBe('Remote edit');
});

it('keeps pending replies unread until the answer actually arrives', function (): void {
    $request = PageMessage::query()->create(['room' => hash('sha256', '/projects'), 'user_id' => auth()->id(), 'body' => 'Question']);
    $reply = PageMessage::query()->create(['room' => $request->room, 'parent_id' => $request->id, 'is_agent' => true, 'body' => 'Thinking…', 'agent_status' => 'pending']);
    $chat = Livewire::test(PageChat::class, ['page' => '/projects'])->call('openThread', $request->id)->set('isOpen', true);
    expect($reply->reads()->count())->toBe(0);
    $chat->set('isOpen', false);
    $reply->update(['body' => 'The answer', 'agent_status' => 'completed']);
    expect(PageMessage::query()->unreadFor(auth()->id())->pluck('id')->all())->toContain($reply->id);
});

it('reads infolist state and project history without exposing another projects history', function (): void {
    $project = Project::factory()->create(['name' => 'Original']);
    $project->update(['name' => 'Reviewed']);
    $other = Project::factory()->create(['name' => 'Private other project']);
    $page = new PageInteraction(Livewire::test(ViewProject::class, ['record' => $project->id])->instance(), new PageMessage);
    expect($page->inspect()['record']['name'])->toBe('Reviewed')
        ->and(json_encode($page->history()))->toContain('Original', 'Reviewed')->not->toContain('Private other project');
});

it('searches sorts and filters the current table and keeps confirmation actions unexecuted', function (): void {
    $target = Project::factory()->create(['name' => 'Zebra target', 'status' => ProjectStatus::Active]);
    Project::factory()->create(['name' => 'Other', 'status' => ProjectStatus::Active]);
    Project::factory()->create(['name' => 'Zebra excluded', 'status' => ProjectStatus::Completed]);
    $component = Livewire::test(ListProjects::class);
    $page = new PageInteraction($component->instance(), new PageMessage);
    $filters = $page->inspect()['filters'];
    $statusPath = collect(array_keys($filters))->first(fn (string $path): bool => str_contains($path, 'status.'));
    $state = $page->operate('configure_table', ['search' => 'Zebra', 'sort' => 'name', 'direction' => 'desc', 'filters' => [$statusPath => 'active']]);
    expect($state['sort'])->toBe('name:desc')
        ->and(array_column($state['rows'], 'name'))->toBe(['Zebra target']);
    $result = $page->operate('run_action', ['name' => 'complete', 'record_key' => (string) $target->id]);
    expect($result)->toContain('NOT executed')
        ->and($target->refresh()->status)->toBe(ProjectStatus::Active)
        ->and($component->instance()->getMountedAction()->getName())->toBe('complete');
});

it('excludes URL actions and invokes immediate native row actions', function (): void {
    $project = Project::factory()->create(['status' => ProjectStatus::OnHold]);
    $page = new PageInteraction(Livewire::test(ListProjects::class)->instance(), new PageMessage);
    expect(fn () => $page->operate('run_action', ['name' => 'edit', 'record_key' => (string) $project->id]))->toThrow(ValidationException::class);
    $page->operate('run_action', ['name' => 'resume', 'record_key' => (string) $project->id]);
    expect($project->refresh()->status)->toBe(ProjectStatus::Active);
});

it('runs a mentioned request once on its authorized page and persists the reply', function (): void {
    PageAssistant::fake([
        new ToolCall('change-name', 'UsePage', ['operation' => 'update_form', 'input' => '{"data.name":"Tool loop draft"}']),
        'This is the project editor.',
    ]);
    $project = Project::factory()->create(['name' => 'Persisted name']);
    $project->update(['budget' => 2345]);
    Livewire::test(PageChat::class, ['page' => '/projects/' . $project->id . '/edit'])
        ->set('body', '<p><span data-type="mention" data-id="agent" data-label="Agent" data-char="@">@Agent</span> Which page is this?</p>')->call('send');
    $reply = PageMessage::query()->where('is_agent', true)->sole();
    $component = Livewire::test(EditProject::class, ['record' => $project->id]);
    $component->call('pageAgentReply', $reply->id)->assertOk()->assertSet('data.name', 'Persisted name')
        ->assertSet('agentProposal.name.proposed', 'Tool loop draft');
    expect($reply->refresh()->agent_status)->toBe('completed')
        ->and($reply->body)->toBe('This is the project editor.')
        ->and($project->refresh()->name)->toBe('Persisted name');
    $component->call('pageAgentReply', $reply->id)->assertOk();
    PageAssistant::assertPromptedTimes(1);
    PageAssistant::assertPrompted(fn ($prompt): bool => $prompt->contains('Recent saved project history') && $prompt->contains('$2,345.00'));
});

it('answers unmentioned follow ups from another participant only in the agents thread', function (): void {
    PageAssistant::fake(['Here is the follow up.']);
    $chat = Livewire::test(PageChat::class, ['page' => '/projects'])
        ->set('body', '<p><span data-type="mention" data-id="agent" data-label="Agent" data-char="@">@Agent</span> Help</p>')->call('send');
    $root = PageMessage::query()->where('is_agent', false)->sole();
    $this->actingAs(User::factory()->create());
    $chat = Livewire::test(PageChat::class, ['page' => '/projects'])->call('openThread', $root->id)
        ->set('body', '<p>Tell me more.</p>')->call('send')->assertDispatched('page-agent-request');
    $reply = PageMessage::query()->where('is_agent', true)->latest('id')->firstOrFail();
    expect($reply->agentRequest->mentionsAgent())->toBeFalse()
        ->and($reply->parent_id)->toBe($root->id)
        ->and($reply->shouldReceiveAgentReply())->toBeFalse();
    Livewire::test(ListProjects::class)->call('pageAgentReply', $reply->id)->assertOk();
    expect($reply->refresh()->body)->toBe('Here is the follow up.');

    $chat->call('openThread')->set('body', '<p>A separate conversation</p>')->call('send')->assertNotDispatched('page-agent-request');
    $otherRoot = PageMessage::query()->whereNull('parent_id')->latest('id')->firstOrFail();
    $chat->call('openThread', $otherRoot->id)->set('body', '<p>A human-only reply</p>')->call('send')->assertNotDispatched('page-agent-request');
    expect(PageMessage::query()->where('is_agent', true)->count())->toBe(2);
    PageAssistant::assertPromptedTimes(1);
    PageAssistant::assertPrompted(fn ($prompt): bool => $prompt->contains('Recent page conversation') && $prompt->contains('Help') && $prompt->contains('Tell me more.'));
});

it('broadcasts partial text while running and persists the complete streamed reply', function (): void {
    PageAssistant::fake(['A streamed response arrives in parts.']);
    $chunks = [];
    Event::listen(PageAgentStreamed::class, function (PageAgentStreamed $event) use (&$chunks): void {
        $chunks[] = ['text' => $event->text, 'status' => PageMessage::findOrFail($event->messageId)->agent_status, 'channel' => $event->broadcastOn()->name];
    });
    Livewire::test(PageChat::class, ['page' => '/projects'])
        ->set('body', '<p><span data-type="mention" data-id="agent" data-label="Agent" data-char="@">@Agent</span> Reply</p>')->call('send');
    $reply = PageMessage::query()->where('is_agent', true)->sole();
    Livewire::test(ListProjects::class)->call('pageAgentReply', $reply->id)->assertOk();
    expect($chunks)->not->toBeEmpty()
        ->and($chunks[0]['status'])->toBe('running')
        ->and($chunks[0]['text'])->not->toBe('A streamed response arrives in parts.')
        ->and($chunks[0]['channel'])->toBe('private-page-chat.' . $reply->room)
        ->and($reply->refresh()->body)->toBe('A streamed response arrives in parts.')
        ->and($reply->agent_status)->toBe('completed');
});

it('does not let a pending reply make its own unmentioned request eligible', function (): void {
    PageAssistant::fake();
    $root = PageMessage::query()->create(['room' => hash('sha256', '/projects'), 'user_id' => auth()->id(), 'body' => 'Human conversation']);
    $request = PageMessage::query()->create(['room' => $root->room, 'parent_id' => $root->id, 'user_id' => auth()->id(), 'body' => 'No invitation']);
    $reply = PageMessage::query()->create(['room' => $root->room, 'parent_id' => $root->id, 'is_agent' => true, 'agent_request_id' => $request->id, 'agent_status' => 'pending', 'body' => 'Thinking…']);
    Livewire::test(ListProjects::class)->call('pageAgentReply', $reply->id)->assertForbidden();
    PageAssistant::assertPromptedTimes(0);
});

it('configures and mounts grouped row actions inside a hydrated tool request', function (): void {
    $project = Project::factory()->create(['name' => 'Find target', 'status' => ProjectStatus::Active]);
    PageAssistant::fake([
        new ToolCall('search', 'UsePage', ['operation' => 'configure_table', 'input' => '{"search":"Find target","sort":"name","direction":"desc"}']),
        new ToolCall('action', 'UsePage', ['operation' => 'run_action', 'input' => json_encode(['name' => 'complete', 'record_key' => (string) $project->id])]),
        'Please confirm the action.',
    ]);
    Livewire::test(PageChat::class, ['page' => '/projects'])
        ->set('body', '<p><span data-type="mention" data-id="agent" data-label="Agent" data-char="@">@Agent</span> Find target and complete it.</p>')->call('send');
    $reply = PageMessage::query()->where('is_agent', true)->sole();
    Livewire::test(ListProjects::class)->call('pageAgentReply', $reply->id)
        ->assertSet('tableSearch', 'Find target')->assertSet('tableSort', 'name:desc')
        ->assertSet('mountedActions.0.name', 'complete');
    expect($reply->refresh()->agent_status)->toBe('completed')
        ->and($project->refresh()->status)->toBe(ProjectStatus::Active);
});

it('rejects cross page and cross user agent requests before calling the provider', function (): void {
    PageAssistant::fake();
    $project = Project::factory()->create();
    $other = Project::factory()->create();
    Livewire::test(PageChat::class, ['page' => '/projects/' . $project->id])
        ->set('body', '<p><span data-type="mention" data-id="agent" data-label="Agent" data-char="@">@Agent</span> Help</p>')->call('send');
    $reply = PageMessage::query()->where('is_agent', true)->sole();
    Livewire::test(EditProject::class, ['record' => $other->id])->call('pageAgentReply', $reply->id)->assertForbidden();
    $this->actingAs(User::factory()->create());
    Livewire::test(EditProject::class, ['record' => $project->id])->call('pageAgentReply', $reply->id)->assertForbidden();
    PageAssistant::assertPromptedTimes(0);
    expect($reply->refresh()->agent_status)->toBe('pending');
});

it('reads room discussion with threads but excludes other rooms and pending agent text', function (): void {
    $request = PageMessage::query()->create(['room' => 'one', 'user_id' => auth()->id(), 'body' => 'Root', 'body_format' => 'text']);
    PageMessage::query()->create(['room' => 'one', 'parent_id' => $request->id, 'body' => 'Thread context', 'body_format' => 'text']);
    PageMessage::query()->create(['room' => 'two', 'body' => 'Other room', 'body_format' => 'text']);
    PageMessage::query()->create(['room' => 'one', 'body' => 'Thinking…', 'body_format' => 'text', 'agent_status' => 'pending']);
    $page = new PageInteraction(Livewire::test(ListProjects::class)->instance(), $request);
    expect(json_encode($page->discussion()))->toContain('Root', 'Thread context')->not->toContain('Other room', 'Thinking');
});

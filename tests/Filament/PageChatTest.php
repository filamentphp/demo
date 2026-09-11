<?php

use App\Events\PageChatChanged;
use App\Livewire\PageChat;
use App\Models\HR\Project;
use App\Models\PageMessage;
use App\Models\PageMessageRead;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;

function pageMessage(string $page, User $user, string $body, ?PageMessage $parent = null, array $attributes = []): PageMessage
{
    return PageMessage::query()->create([
        'room' => hash('sha256', $page),
        'user_id' => $user->id,
        'parent_id' => $parent?->id,
        'body' => $body,
        'body_format' => 'html',
        ...$attributes,
    ]);
}

it('stores rich text creates and edits as html and broadcasts them in the canonical room', function (): void {
    Event::fake([PageChatChanged::class]);

    $page = Livewire::test(PageChat::class, ['page' => '/projects/22/edit'])
        ->set('body', '<p><strong>Ready</strong> for review</p>')
        ->call('send')
        ->assertHasNoErrors();

    $root = PageMessage::query()->sole();
    expect($root)
        ->body->toContain('<strong>Ready</strong>')
        ->body_format->toBe('html')
        ->user_id->toBe(auth()->id())
        ->parent_id->toBeNull()
        ->room->toBe(hash('sha256', '/projects/22'));

    $page->call('edit', $root->id)
        ->set('editBody', '<p>Ready for <em>final</em> review</p>')
        ->call('saveEdit')
        ->assertHasNoErrors();

    expect($root->refresh())
        ->body->toContain('<em>final</em>')
        ->body_format->toBe('html')
        ->edited_at->not->toBeNull();
    Event::assertDispatched(PageChatChanged::class, fn (PageChatChanged $event): bool => $event->operation === 'updated' && $event->room === hash('sha256', '/projects/22'));
});

it('shares one room between project view and edit paths while isolating other projects', function (): void {
    Livewire::test(PageChat::class, ['page' => '/projects/22/edit'])
        ->set('body', '<p>Shared project conversation</p>')
        ->call('send');

    Livewire::test(PageChat::class, ['page' => '/projects/22'])
        ->assertSee('Shared project conversation');
    Livewire::test(PageChat::class, ['page' => '/projects/23'])
        ->assertDontSee('Shared project conversation');
});

it('prevents cross-room and cross-user access to threads and messages', function (): void {
    $owner = auth()->user();
    $message = pageMessage('/projects/22', $owner, '<p>Owner only</p>');

    expect(fn () => Livewire::test(PageChat::class, ['page' => '/projects/23'])->call('openThread', $message->id))
        ->toThrow(ModelNotFoundException::class);
    expect(fn () => Livewire::test(PageChat::class, ['page' => '/projects/23'])->call('deleteMessage', $message->id))
        ->toThrow(ModelNotFoundException::class);

    $this->actingAs(User::factory()->create());
    expect(fn () => Livewire::test(PageChat::class, ['page' => '/projects/22'])->call('edit', $message->id))
        ->toThrow(ModelNotFoundException::class);
    expect(fn () => Livewire::test(PageChat::class, ['page' => '/projects/22'])->call('deleteMessage', $message->id))
        ->toThrow(ModelNotFoundException::class);

    expect($message->refresh()->body)->toContain('Owner only');
});

it('rejects visually empty and oversized rich text while allowing formatting within both limits', function (): void {
    $page = Livewire::test(PageChat::class, ['page' => '/']);

    $page->set('body', '<p><br></p><p>&nbsp; </p>')->call('send')->assertHasErrors(['body']);
    $page->set('body', '<p>' . str_repeat('a', 4001) . '</p>')->call('send')->assertHasErrors(['body']);
    $page->set('body', str_repeat('<p><strong>a</strong></p>', 1001))->call('send')->assertHasErrors(['body' => 'max']);
    $page->set('body', '<p><strong>' . str_repeat('a', 3990) . '</strong></p>')->call('send')->assertHasNoErrors();

    expect(PageMessage::query()->count())->toBe(1);
});

it('sanitizes rendered rich content and recognizes only real mention nodes', function (): void {
    $mentioned = User::factory()->create(['name' => 'Actual Mention']);
    $other = User::factory()->create(['name' => 'Other Person']);
    $message = pageMessage('/', auth()->user(), '<p>Hello <span data-type="mention" data-id="' . $mentioned->id . '" data-label="Actual Mention" data-char="@">@Actual Mention</span> @Other Person</p><script>alert(1)</script><img src=x onerror=alert(2)>');

    $html = $message->contentHtml();
    expect($html)
        ->toContain('Actual Mention')
        ->not->toContain('<script')
        ->not->toContain('onerror')
        ->and($message->mentionsUser($mentioned->id))->toBeTrue()
        ->and($message->mentionsUser($other->id))->toBeFalse();
});

it('resolves mention searches and labels to the correct user identities', function (): void {
    $alex = User::factory()->create(['name' => 'Alex Rivera']);
    $alexa = User::factory()->create(['name' => 'Alexa Stone']);
    User::factory()->create(['name' => 'Jordan Lee']);
    $provider = PageMessage::mentionProvider();

    expect($provider->getSearchResults('Alex'))
        ->toMatchArray(['user:' . $alex->id => 'Alex Rivera', 'user:' . $alexa->id => 'Alexa Stone'])
        ->not->toHaveKey('user:' . auth()->id())
        ->and($provider->getLabels([$alexa->id, $alex->id, 999999]))
        ->toMatchArray([$alex->id => 'Alex Rivera', $alexa->id => 'Alexa Stone'])
        ->not->toHaveKey(999999);
});

it('pins one bodyless root to an activity and validates its project scope', function (): void {
    $project = Project::factory()->create();
    $otherProject = Project::factory()->create();
    $activity = $project->activities()->latest('id')->firstOrFail();
    $foreignActivity = $otherProject->activities()->latest('id')->firstOrFail();
    $page = Livewire::test(PageChat::class, ['page' => "/projects/{$project->id}/edit"]);

    $page->call('replyToActivity', $activity->id)->call('replyToActivity', $activity->id);

    $root = PageMessage::query()->where('activity_id', $activity->id)->sole();
    expect($root)
        ->body->toBeNull()
        ->user_id->toBeNull()
        ->parent_id->toBeNull()
        ->room->toBe(hash('sha256', "/projects/{$project->id}"));
    expect(PageMessage::query()->where('activity_id', $activity->id)->count())->toBe(1);
    expect(fn () => $page->call('replyToActivity', $foreignActivity->id))->toThrow(ModelNotFoundException::class);
});

it('hides resolved roots by default and blocks replies until the current root is reopened', function (): void {
    $root = pageMessage('/projects/22', auth()->user(), '<p>Resolve me</p>');
    $page = Livewire::test(PageChat::class, ['page' => '/projects/22'])->call('openThread', $root->id);

    $page->call('toggleResolved');
    expect($root->refresh()->resolved_at)->not->toBeNull();
    Livewire::test(PageChat::class, ['page' => '/projects/22'])->assertDontSee('Resolve me')
        ->set('showResolved', true)->assertSee('Resolve me');
    expect(fn () => $page->set('body', '<p>Reply while closed</p>')->call('send'))
        ->toThrow(ModelNotFoundException::class);

    $page->call('toggleResolved')->set('body', '<p>Reply after reopening</p>')->call('send')->assertHasNoErrors();
    expect($root->refresh()->resolved_at)->toBeNull()
        ->and($root->replies()->sole()->body)->toContain('Reply after reopening');
});

it('opens only a validated canonical root from the chat query string', function (): void {
    $root = pageMessage('/projects/22', auth()->user(), '<p>Linked root</p>');
    $reply = pageMessage('/projects/22', auth()->user(), '<p>Not a root</p>', $root);
    $foreign = pageMessage('/projects/23', auth()->user(), '<p>Foreign root</p>');

    Livewire::withQueryParams(['chat' => $root->id])
        ->test(PageChat::class, ['page' => '/projects/22/edit'])
        ->assertSet('isOpen', true)
        ->assertSet('threadId', $root->id);
    expect(fn () => Livewire::withQueryParams(['chat' => $reply->id])->test(PageChat::class, ['page' => '/projects/22']))
        ->toThrow(ModelNotFoundException::class);
    expect(fn () => Livewire::withQueryParams(['chat' => $foreign->id])->test(PageChat::class, ['page' => '/projects/22']))
        ->toThrow(ModelNotFoundException::class);
});

it('persists reads for visible roots and the current thread without reading hidden replies', function (): void {
    $viewer = auth()->user();
    $sender = User::factory()->create();
    $visibleRoot = pageMessage('/projects/22', $sender, '<p>Visible root</p>');
    $visibleReply = pageMessage('/projects/22', $sender, '<p>Visible thread reply</p>', $visibleRoot);
    $otherRoot = pageMessage('/projects/22', $sender, '<p>Other root</p>');
    $hiddenReply = pageMessage('/projects/22', $sender, '<p>Hidden thread reply</p>', $otherRoot);
    $resolvedRoot = pageMessage('/projects/22', $sender, '<p>Resolved root</p>', attributes: ['resolved_at' => now()]);

    $page = Livewire::test(PageChat::class, ['page' => '/projects/22']);
    expect($page->get('unreadIds'))->toContain($visibleRoot->id, $visibleReply->id, $hiddenReply->id, $resolvedRoot->id);
    $page->set('isOpen', true);

    expect(PageMessageRead::query()->where('user_id', $viewer->id)->pluck('page_message_id')->all())
        ->toContain($visibleRoot->id, $otherRoot->id)
        ->not->toContain($visibleReply->id, $hiddenReply->id, $resolvedRoot->id);

    $page->call('openThread', $visibleRoot->id);
    expect(PageMessageRead::query()->where('user_id', $viewer->id)->pluck('page_message_id')->all())
        ->toContain($visibleRoot->id, $visibleReply->id)
        ->not->toContain($hiddenReply->id, $resolvedRoot->id);
    $this->assertDatabaseCount('page_message_reads', 3);
});

it('renders database unread totals and unread reply counts independently of the marker snapshot', function (): void {
    $sender = User::factory()->create();
    $root = pageMessage('/projects/22', $sender, '<p>Root</p>');
    pageMessage('/projects/22', $sender, '<p>Unread reply one</p>', $root);
    pageMessage('/projects/22', $sender, '<p>Unread reply two</p>', $root);
    $component = Livewire::test(PageChat::class, ['page' => '/projects/22']);
    $component->instance()->unreadIds = [];

    $data = $component->instance()->render()->getData();
    $renderedRoot = $data['entries']->firstWhere('id', $root->id);
    expect($data['unreadCount'])->toBe(3)
        ->and($renderedRoot->unread_replies_count)->toBe(2);
});

it('keeps persisted read rows unique across repeated opens', function (): void {
    $message = pageMessage('/projects/22', User::factory()->create(), '<p>Read once</p>');
    $page = Livewire::test(PageChat::class, ['page' => '/projects/22']);

    $page->set('isOpen', true)->set('isOpen', false)->set('isOpen', true);

    expect(PageMessageRead::query()->where('page_message_id', $message->id)->count())->toBe(1);
});

it('rejects unauthenticated chat access', function (): void {
    auth()->logout();

    Livewire::test(PageChat::class, ['page' => '/projects'])->assertForbidden();
});

it('sends temporary mention notifications only to closed viewers on the same record', function (): void {
    $viewer = auth()->user();
    $sender = User::factory()->create(['name' => 'Alex']);
    $message = pageMessage('/projects/22', $sender, '<p>Hello <span data-type="mention" data-id="' . $viewer->id . '" data-char="@" data-label="Viewer">@Viewer</span></p>');
    $event = ['room' => $message->room, 'messageId' => $message->id, 'operation' => 'created'];
    $page = Livewire::test(PageChat::class, ['page' => '/projects/22/edit']);
    $page->call('chatChanged', $event)->assertNotified('Alex mentioned you');
    $this->assertDatabaseCount('notifications', 0);
    $page->set('isOpen', true)->call('chatChanged', $event)->assertNotNotified();
    $page->set('isOpen', false)->call('chatChanged', [...$event, 'operation' => 'updated'])->assertNotNotified();
    Livewire::test(PageChat::class, ['page' => '/projects/23'])->call('chatChanged', $event)->assertNotNotified();
    $this->actingAs($sender);
    Livewire::test(PageChat::class, ['page' => '/projects/22'])->call('chatChanged', $event)->assertNotNotified();
});

it('preserves replies when the author deletes a rich message', function (): void {
    Event::fake([PageChatChanged::class]);
    $root = pageMessage('/projects/22', auth()->user(), '<p>Remove this</p>');
    $reply = pageMessage('/projects/22', User::factory()->create(), '<p>Keep this reply</p>', $root);
    Livewire::test(PageChat::class, ['page' => '/projects/22'])->call('openThread', $root->id)
        ->call('deleteMessage', $root->id)->assertSee('Message deleted')->assertSee('Keep this reply');
    expect($root->refresh()->body)->toBeNull()
        ->and($reply->refresh()->body)->toContain('Keep this reply');
    Event::assertDispatched(PageChatChanged::class, fn (PageChatChanged $event): bool => $event->operation === 'deleted');
});

it('does not mark older messages read when newer history entries fill the feed', function (): void {
    $project = Project::factory()->create();
    $message = pageMessage('/projects/' . $project->id, User::factory()->create(), '<p>Older unread message</p>', attributes: ['created_at' => now()->subDay()]);
    for ($i = 0; $i < 31; $i++) {
        $project->activities()->create(['event' => 'project_updated', 'actor_name' => 'Demo', 'interface' => 'panel', 'changes' => []]);
    }
    $page = Livewire::test(PageChat::class, ['page' => '/projects/' . $project->id])->set('isOpen', true);
    expect(PageMessageRead::query()->where('page_message_id', $message->id)->exists())->toBeFalse();
    $page->call('loadMore');
    expect(PageMessageRead::query()->where('page_message_id', $message->id)->exists())->toBeTrue();
});

it('preloads Agent first and preserves new and legacy user mentions', function (): void {
    $user = User::factory()->create(['name' => 'Aaron Agent']);
    $provider = PageMessage::mentionProvider();
    expect(array_key_first($provider->getItems()))->toBe('agent')
        ->and(array_key_first($provider->getSearchResults('Agent')))->toBe('agent')
        ->and($provider->getItems())->toHaveKey('user:' . $user->id, 'Aaron Agent')
        ->and($provider->getLabels(['user:' . $user->id, (string) $user->id]))->toBe(['user:' . $user->id => 'Aaron Agent', $user->id => 'Aaron Agent']);

    foreach (['user:' . $user->id, (string) $user->id] as $id) {
        $message = pageMessage('/', auth()->user(), '<p><span data-type="mention" data-id="' . $id . '" data-label="Old name" data-char="@">@Old name</span></p>');
        expect($message->mentionsUser($user->id))->toBeTrue()
            ->and($message->contentHtml())->toContain('Aaron Agent');
    }
});

it('reserves the Agent mention and recognizes only its rich mention node', function (): void {
    $provider = PageMessage::mentionProvider();
    $realMention = pageMessage('/', auth()->user(), '<p><span data-type="mention" data-id="agent" data-label="Agent" data-char="@">@Agent</span></p>');
    $plainText = pageMessage('/', auth()->user(), '<p>Please ask @Agent about this.</p>');

    expect($provider->getSearchResults('Age'))->toHaveKey('agent', 'Agent')
        ->and($provider->getLabels(['agent']))->toBe(['agent' => 'Agent'])
        ->and($realMention->mentionsAgent())->toBeTrue()
        ->and($plainText->mentionsAgent())->toBeFalse();
});

it('creates a pending Agent reply in the original thread and dispatches it without a created broadcast', function (): void {
    Event::fake([PageChatChanged::class]);
    $mention = '<p>Help <span data-type="mention" data-id="agent" data-label="Agent" data-char="@">@Agent</span></p>';

    $component = Livewire::test(PageChat::class, ['page' => '/projects/22'])
        ->set('body', $mention)
        ->call('send')
        ->assertSet('isOpen', true)
        ->assertDispatched('page-agent-request');

    $request = PageMessage::query()->where('is_agent', false)->sole();
    $reply = PageMessage::query()->where('is_agent', true)->sole();
    expect($reply)
        ->user_id->toBeNull()
        ->body->toBe('Thinking…')
        ->body_format->toBe('text')
        ->agent_status->toBe('pending')
        ->agent_request_id->toBe($request->id)
        ->parent_id->toBe($request->id)
        ->and($reply->agentRequest->is($request))->toBeTrue()
        ->and($component->get('threadId'))->toBe($request->id);

    Event::assertDispatched(PageChatChanged::class, fn (PageChatChanged $event): bool => $event->messageId === $reply->id && $event->operation === 'updated');
    Event::assertNotDispatched(PageChatChanged::class, fn (PageChatChanged $event): bool => $event->messageId === $reply->id && $event->operation === 'created');

    Livewire::test(PageChat::class, ['page' => '/projects/22'])
        ->call('openThread', $request->id)
        ->set('body', $mention)
        ->call('send');
    $threadRequest = PageMessage::query()->where('is_agent', false)->latest('id')->firstOrFail();
    expect($threadRequest->parent_id)->toBe($request->id)
        ->and(PageMessage::query()->where('agent_request_id', $threadRequest->id)->sole()->parent_id)->toBe($request->id);
});

it('pauses automatic agent replies and resumes through a mention or the thread control', function (): void {
    Event::fake([PageChatChanged::class]);
    $mention = '<p><span data-type="mention" data-id="agent" data-label="Agent" data-char="@">@Agent</span> Help</p>';
    $chat = Livewire::test(PageChat::class, ['page' => '/projects'])->set('body', $mention)->call('send');
    $root = PageMessage::query()->whereNull('parent_id')->sole();
    $chat->assertSee('Agent is participating')->call('toggleAgentPaused')->assertSee('Agent paused');
    expect($root->refresh()->agent_paused)->toBeTrue();
    $chat->set('body', '<p>Humans talking</p>')->call('send')->assertNotDispatched('page-agent-request');
    expect(PageMessage::query()->where('is_agent', true)->count())->toBe(1);
    $chat->set('body', $mention)->call('send')->assertDispatched('page-agent-request')->assertSee('Agent is participating');
    expect($root->refresh()->agent_paused)->toBeFalse();
    $chat->call('toggleAgentPaused')->call('toggleAgentPaused')->assertSee('Agent is participating');
    $chat->set('body', '<p>Continue</p>')->call('send')->assertDispatched('page-agent-request');
    expect(PageMessage::query()->where('is_agent', true)->count())->toBe(3);
});

it('rejects spoofed unavailable Agent updates and fails only the current users pending request', function (): void {
    Event::fake([PageChatChanged::class]);
    $owner = auth()->user();
    $other = User::factory()->create();
    $request = pageMessage('/projects/22', $owner, '<p>Request</p>');
    $reply = PageMessage::query()->create([
        'room' => $request->room,
        'parent_id' => $request->id,
        'body' => 'Thinking…',
        'body_format' => 'text',
        'is_agent' => true,
        'agent_request_id' => $request->id,
        'agent_status' => 'pending',
    ]);

    expect(fn () => Livewire::test(PageChat::class, ['page' => '/projects/23'])->call('agentUnavailable', $reply->id))
        ->toThrow(ModelNotFoundException::class);
    $this->actingAs($other);
    expect(fn () => Livewire::test(PageChat::class, ['page' => '/projects/22'])->call('agentUnavailable', $reply->id))
        ->toThrow(ModelNotFoundException::class);

    $this->actingAs($owner);
    Livewire::test(PageChat::class, ['page' => '/projects/22'])->call('agentUnavailable', $reply->id);
    expect($reply->refresh())
        ->agent_status->toBe('failed')
        ->body->toContain('not available');
    Event::assertDispatched(PageChatChanged::class, fn (PageChatChanged $event): bool => $event->messageId === $reply->id && $event->operation === 'created');
});

it('counts null-user Agent messages as unread and presents Agent identity without edit controls', function (): void {
    $request = pageMessage('/projects/22', User::factory()->create(), '<p>Request</p>');
    $reply = PageMessage::query()->create([
        'room' => $request->room,
        'parent_id' => $request->id,
        'body' => 'Agent answer',
        'body_format' => 'text',
        'is_agent' => true,
        'agent_request_id' => $request->id,
        'agent_status' => 'completed',
    ]);

    expect($reply->authorName())->toBe('Agent')
        ->and(PageMessage::query()->unreadFor(auth()->id())->pluck('id')->all())->toContain($reply->id);

    Livewire::test(PageChat::class, ['page' => '/projects/22'])
        ->call('openThread', $request->id)
        ->assertSee('Agent answer')
        ->assertSee('Message from Agent')
        ->assertDontSeeHtml('wire:click="edit(' . $reply->id . ')"');
});

it('formats Agent paragraphs and lists without rendering unsafe HTML or links', function (): void {
    $message = new PageMessage(['is_agent' => true, 'body' => "**Summary**\n\n- First item\n- Second item\n\n<script>alert(1)</script>\n\n[Unsafe](javascript:alert%281%29)"]);

    expect($message->contentHtml())->toContain('<strong>Summary</strong>', '<ul>', '<li>First item</li>')
        ->not->toContain('<script>', 'href="javascript:');
});

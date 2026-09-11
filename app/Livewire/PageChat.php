<?php

namespace App\Livewire;

use App\Events\PageChatChanged;
use App\Models\HR\Project;
use App\Models\HR\ProjectActivity;
use App\Models\PageMessage;
use App\Models\PageMessageRead;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Facades\Filament;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * @property-read Schema $form
 * @property-read Schema $editForm
 */
class PageChat extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    protected User $chatUser;

    #[Url(as: 'chat')]
    public ?int $linkedThread = null;

    /** @var array<int> */
    #[Locked]
    public array $unreadIds = [];

    public bool $showResolved = false;

    #[Locked]
    public string $room;

    #[Locked]
    public string $page;

    #[Locked]
    public ?int $projectId = null;

    #[Locked]
    public ?string $projectField = null;

    #[Locked]
    public ?string $previousProjectVisit = null;

    #[Locked]
    public ?int $threadId = null;

    #[Locked]
    public ?int $editingId = null;

    #[Locked]
    public int $limit = 30;

    public bool $isOpen = false;

    /** @var array<string, mixed>|string|null */
    public array | string | null $body = '';

    /** @var array<string, mixed>|string|null */
    public array | string | null $editBody = '';

    public function form(Schema $schema): Schema
    {
        return $schema->components([$this->editor('body')->label('Message')]);
    }

    public function editForm(Schema $schema): Schema
    {
        return $schema->components([$this->editor('editBody')->label('Edit message')]);
    }

    protected function editor(string $field): RichEditor
    {
        return RichEditor::make($field)->hiddenLabel()->required()->maxLength(16000)
            ->minHeight('4.5rem')->maxHeight('12rem')
            ->placeholder('Write a message… Type @ to mention')
            ->toolbarButtons([['bold', 'italic', 'link', 'bulletList', 'orderedList']])
            ->mentions([PageMessage::mentionProvider()]);
    }

    public function boot(): void
    {
        $user = auth()->user();
        abort_unless($user && $user->canAccessPanel(Filament::getPanel('admin')), 403);
        $this->chatUser = $user;
    }

    public function mount(string $page): void
    {
        $this->page = '/' . trim($page, '/');

        if (preg_match('#^/projects/(\d+)(?:/edit)?$#', $this->page, $matches)) {
            $this->projectId = (int) $matches[1];
            $this->page = '/projects/' . $this->projectId;
            $visitKey = 'project-visit:' . auth()->id() . ':' . $this->projectId;
            $this->previousProjectVisit = Cache::get($visitKey);
            Cache::put($visitKey, now()->toIso8601String(), now()->addDays(30));
        }
        $this->room = hash('sha256', $this->page);
        $this->form->fill();
        $this->unreadIds = $this->messageQuery()->unreadFor($this->chatUser->id)->pluck('id')->all();

        if ($this->linkedThread !== null) {
            $this->openChat($this->room, $this->linkedThread);
        }
    }

    /** @return array<string, string> */
    public function getListeners(): array
    {
        return ['echo-private:page-chat.' . $this->room . ',PageChatChanged' => 'chatChanged'];
    }

    /** @param array{room: string, messageId: int, operation: string} $event */
    public function chatChanged(array $event): void
    {
        if ($event['room'] !== $this->room) {
            return;
        }

        $message = $this->messageQuery()->with('user')->find($event['messageId']);

        if (($event['operation'] === 'created') && ! $this->isOpen && $message?->body && ($message->user_id !== auth()->id())) {
            Notification::make('page-chat-' . $message->id)
                ->title($message->authorName() . ($message->mentionsUser($this->chatUser->id) ? ' mentioned you' : ' sent a message'))
                ->body(e(str(html_entity_decode(strip_tags($message->contentHtml())))->limit(100)->toString()))
                ->icon('heroicon-o-chat-bubble-left-right')
                ->duration(6000)
                ->actions([
                    Action::make('open')->label('Open conversation')->button()
                        ->dispatch('open-page-chat', ['room' => $this->room, 'threadId' => $message->parent_id ?? $message->id]),
                ])
                ->send();
        }

        if ($event['operation'] === 'created') {
            $this->unreadIds = array_unique([...$this->unreadIds, ...$this->messageQuery()->unreadFor($this->chatUser->id)->pluck('id')->all()]);
            $this->markVisibleRead();
            $this->dispatch('page-chat-scroll', room: $this->room);
        }
    }

    /** @param array{projectId: int} $event */
    #[On('echo-private:projects,ProjectChanged')]
    public function projectChanged(array $event): void
    {
        if ($event['projectId'] !== $this->projectId) {
            $this->skipRender();
        }
    }

    #[On('open-page-chat')]
    public function openChat(string $room, ?int $threadId = null): void
    {
        if ($room !== $this->room) {
            return;
        }

        if ($threadId !== null) {
            $this->openThread($threadId);
        }

        $this->isOpen = true;
        $this->markVisibleRead();
    }

    public function openThread(?int $id = null): void
    {
        if ($id !== null) {
            $this->messageQuery()->whereNull('parent_id')->findOrFail($id);
        }

        $this->threadId = $id;
        $this->linkedThread = $id;
        $this->reset('body', 'editingId', 'editBody', 'limit', 'projectField');
        $this->form->fill(['body' => '']);
        $this->resetValidation();
        $this->markVisibleRead();
        $this->dispatch('page-chat-scroll', room: $this->room, force: true);
    }

    public function send(): void
    {
        $body = $this->form->getState()['body'];
        $this->validateContent('body', $body);

        if ($this->threadId !== null) {
            $this->messageQuery()->whereNull('parent_id')->whereNull('resolved_at')->findOrFail($this->threadId);
        }

        $message = PageMessage::query()->create([
            'room' => $this->room,
            'user_id' => auth()->id(),
            'parent_id' => $this->threadId,
            'project_field' => $this->threadId === null ? $this->projectField : null,
            'body' => $body,
            'body_format' => 'html',
        ]);
        $this->form->fill(['body' => '']);
        $this->projectField = null;
        PageChatChanged::dispatch($this->room, $message->id, 'created');
        $this->dispatch('page-chat-scroll', room: $this->room);

        if ($message->shouldReceiveAgentReply()) {
            if ($message->parent_id !== null && $message->mentionsAgent()) {
                $this->messageQuery()->whereKey($message->parent_id)->update(['agent_paused' => false]);
                PageChatChanged::dispatch($this->room, $message->parent_id, 'updated');
            }
            $reply = PageMessage::query()->create([
                'room' => $this->room,
                'user_id' => null,
                'parent_id' => $message->parent_id ?? $message->id,
                'body' => 'Thinking…',
                'body_format' => 'text',
                'is_agent' => true,
                'agent_request_id' => $message->id,
                'agent_status' => 'pending',
            ]);

            PageChatChanged::dispatch($this->room, $reply->id, 'updated');
            $this->isOpen = true;
            $this->openThread($message->parent_id ?? $message->id);
            $this->dispatch('page-agent-request', replyId: $reply->id);
        }
    }

    public function agentUnavailable(int $replyId): void
    {
        $reply = $this->messageQuery()
            ->where('is_agent', true)
            ->where('agent_status', 'pending')
            ->whereHas('agentRequest', fn (Builder $query) => $query->where('room', $this->room)->where('user_id', auth()->id()))
            ->findOrFail($replyId);

        $reply->update([
            'body' => 'Agent is not available on this page.',
            'body_format' => 'text',
            'agent_status' => 'failed',
        ]);

        PageChatChanged::dispatch($this->room, $reply->id, 'created');
    }

    public function edit(int $id): void
    {
        $message = $this->messageQuery()->where('is_agent', false)->where('user_id', auth()->id())->findOrFail($id);
        abort_if($message->body === null, 404);
        $this->editingId = $message->id;
        $this->editForm->fill(['editBody' => $message->contentHtml()]);
        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->reset('editingId', 'editBody');
        $this->resetValidation();
    }

    public function saveEdit(): void
    {
        $body = $this->editForm->getState()['editBody'];
        $this->validateContent('editBody', $body);
        $message = $this->messageQuery()->where('is_agent', false)->where('user_id', auth()->id())->whereNotNull('body')->findOrFail($this->editingId);
        $message->update(['body' => $body, 'body_format' => 'html', 'edited_at' => now()]);
        $this->cancelEdit();
        PageChatChanged::dispatch($this->room, $message->id, 'updated');
    }

    public function deleteMessage(int $id): void
    {
        $message = $this->messageQuery()->where('user_id', auth()->id())->findOrFail($id);
        $message->update(['body' => null]);
        if ($this->editingId === $id) {
            $this->cancelEdit();
        }
        PageChatChanged::dispatch($this->room, $message->id, 'deleted');
    }

    public function deleteMessageAction(): Action
    {
        return Action::make('deleteMessage')->label('Delete message')->color('danger')
            ->requiresConfirmation()->modalDescription('Replies will stay in the conversation.')
            ->action(fn (array $arguments) => $this->deleteMessage($arguments['message']));
    }

    public function loadMore(): void
    {
        $this->limit += 30;
        $this->markVisibleRead();
    }

    protected function validateContent(string $field, string $body): void
    {
        validator([$field => $body], [$field => ['max:16000']])->validate();
        $text = trim(html_entity_decode(RichContentRenderer::make($body)->toText()));
        $isEmpty = ! preg_match('/[^\s\x{00a0}]/u', $text);
        if ($isEmpty || (mb_strlen($text) > 4000)) {
            throw ValidationException::withMessages([$field => $isEmpty ? 'Write a message first.' : 'Keep messages to 4,000 characters.']);
        }
    }

    public function replyToActivity(int $id): void
    {
        $activity = ProjectActivity::query()->where('project_id', $this->projectId)->findOrFail($id);
        $thread = PageMessage::query()->firstOrCreate(['activity_id' => $activity->id], [
            'room' => $this->room, 'created_at' => $activity->created_at,
        ]);
        $this->openThread($thread->id);
    }

    #[On('project-discuss-field')]
    public function discussProjectField(int $projectId, string $field): void
    {
        if ($projectId !== $this->projectId) {
            return;
        }
        abort_unless($field === 'description', 422);
        if (filled(trim(RichContentRenderer::make($this->body)->toText()))) {
            Notification::make()->warning()->title('Finish your current message first')->send();
            $this->isOpen = true;

            return;
        }
        $this->openThread();
        $this->projectField = $field;
        $this->isOpen = true;
    }

    public function catchUpProject(): void
    {
        abort_unless($this->projectId !== null, 403);
        if (filled(trim(RichContentRenderer::make($this->body)->toText()))) {
            Notification::make()->warning()->title('Finish your current message first')->send();

            return;
        }
        $this->openThread();
        $since = $this->previousProjectVisit ?? now()->subDays(7)->toIso8601String();
        $this->form->fill(['body' => '<p><span data-type="mention" data-id="agent" data-label="Agent" data-char="@">@Agent</span> Catch me up on this project since ' . e($since) . '. Read history and chat. Summarize changes, blockers and unresolved discussions. Cite message IDs and history timestamps. Say when available context is incomplete. Do not change any data.</p>']);
        $this->send();
    }

    public function toggleResolved(): void
    {
        $thread = $this->messageQuery()->whereNull('parent_id')->findOrFail($this->threadId);
        $thread->update(['resolved_at' => $thread->resolved_at ? null : now()]);
        PageChatChanged::dispatch($this->room, $thread->id, 'updated');
    }

    public function toggleAgentPaused(): void
    {
        $thread = $this->messageQuery()->whereNull('parent_id')->whereNull('resolved_at')
            ->whereHas('replies', fn (Builder $query) => $query->where('is_agent', true))->findOrFail($this->threadId);
        $thread->update(['agent_paused' => ! $thread->agent_paused]);
        PageChatChanged::dispatch($this->room, $thread->id, 'updated');
    }

    public function updatedIsOpen(): void
    {
        if ($this->isOpen) {
            $this->unreadIds = $this->messageQuery()->unreadFor($this->chatUser->id)->pluck('id')->all();
            $this->markVisibleRead();
        }
    }

    public function updatedShowResolved(): void
    {
        $this->markVisibleRead();
    }

    protected function markVisibleRead(): void
    {
        if (! $this->isOpen) {
            return;
        }
        $visibleIds = $this->feedEntries()->take($this->limit)
            ->filter(fn ($entry): bool => $entry instanceof PageMessage)->pluck('id');
        $ids = $this->messageQuery()->whereKey($visibleIds)->unreadFor($this->chatUser->id)->pluck('id');
        if ($this->threadId !== null) {
            $ids->push($this->threadId);
        }
        $rows = $ids->map(fn (int $id): array => ['user_id' => auth()->id(), 'page_message_id' => $id])->all();
        if ($rows !== []) {
            PageMessageRead::query()->upsert($rows, ['user_id', 'page_message_id'], ['user_id']);
        }
    }

    /** @return Builder<PageMessage> */
    protected function visibleMessageQuery(): Builder
    {
        return $this->messageQuery()->where('parent_id', $this->threadId)
            ->when(! $this->threadId && ! $this->showResolved, fn (Builder $query) => $query->whereNull('resolved_at'));
    }

    /** @return Builder<PageMessage> */
    protected function messageQuery(): Builder
    {
        return PageMessage::query()->where('room', $this->room);
    }

    /** @return Collection<int, PageMessage|ProjectActivity> */
    protected function feedEntries(): Collection
    {
        $messages = $this->visibleMessageQuery()->with(['user', 'activity'])->withCount([
            'replies', 'replies as unread_replies_count' => fn ($query) => $query->unreadFor($this->chatUser->id),
        ])->latest('created_at')->latest('id')->limit($this->limit + 1)->get();
        $activities = $this->projectId && ! $this->threadId
            ? ProjectActivity::query()->where('project_id', $this->projectId)
                ->whereNotIn('id', $this->messageQuery()->whereNotNull('activity_id')->select('activity_id'))
                ->latest('id')->limit($this->limit + 1)->get()
            : collect();

        return $messages->toBase()->concat($activities)->sortByDesc('created_at');
    }

    public function render(): View
    {
        $entries = $this->feedEntries();

        return view('filament.chat.panel', [
            'pageLabel' => $this->projectId
                ? (Project::withTrashed()->whereKey($this->projectId)->value('name') ?? 'Project')
                : (str($this->page)->trim('/')->replace('/', ' · ')->headline()->toString() ?: 'Dashboard'),
            'entries' => $entries->take($this->limit)->reverse(),
            'hasMore' => $entries->count() > $this->limit,
            'thread' => $this->threadId ? $this->messageQuery()->with(['user', 'activity'])
                ->withExists(['replies as has_agent' => fn (Builder $query) => $query->where('is_agent', true)])->findOrFail($this->threadId) : null,
            'unreadCount' => $this->messageQuery()->unreadFor($this->chatUser->id)->count(),
        ]);
    }
}

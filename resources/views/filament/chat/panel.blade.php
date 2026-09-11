<div x-data="{
    open: $wire.entangle('isOpen').live,
    narrow: window.innerWidth < 1280,
    streamListener: null,
    following: true,
    hasNewMessages: false,
    jumpToLatest() {
        this.following = true
        this.hasNewMessages = false
        this.$nextTick(() => requestAnimationFrame(() => { this.$refs.feed.scrollTop = this.$refs.feed.scrollHeight }))
    },
    feedChanged(force = false) {
        if (force || this.following) this.jumpToLatest()
        else this.hasNewMessages = true
    },
    init() {
        this.streamListener = (event) => window.dispatchEvent(new CustomEvent('page-agent-stream', { detail: event }))
        window.Echo.private(@js('page-chat.' . $room)).listen('PageAgentStreamed', this.streamListener)
        this.$watch('open', (value) => {
            if (@js((bool) $projectId)) document.body.classList.toggle('project-activity-open', value)
            if (value) this.jumpToLatest()
        })
        if (@js((bool) $projectId)) document.body.classList.toggle('project-activity-open', this.open)
        if (this.open) this.jumpToLatest()
    },
    destroy() {
        if (@js((bool) $projectId)) document.body.classList.remove('project-activity-open')
        window.Echo.private(@js('page-chat.' . $room)).stopListening('PageAgentStreamed', this.streamListener)
    },
}" x-on:resize.window="narrow = window.innerWidth < 1280" x-on:keydown.escape.window="if (! $wire.mountedActions.length) open = false">
    <div x-show="! open" class="fixed end-6 bottom-6 z-40">
        <x-filament::button icon="heroicon-o-chat-bubble-left-right" x-on:click="open = true" :aria-label="$projectId ? 'Open discussion' : 'Open page chat'">{{ $projectId ? 'Discussion' : 'Page chat' }} @if ($unreadCount)<span class="ms-2 rounded-full bg-white/20 px-2 text-xs">{{ $unreadCount }}</span>@endif</x-filament::button>
    </div>

    <aside x-cloak x-show="open" x-transition.opacity x-trap.noscroll="open && narrow && @js((bool) $projectId) && ! $wire.mountedActions.length" aria-label="{{ $projectId ? 'Activity' : 'Page chat' }}" @class(['project-activity-panel' => $projectId, 'fixed inset-y-0 end-0 z-40 flex w-full flex-col border-s border-gray-200 bg-white shadow-xl dark:border-white/10 dark:bg-gray-900', 'max-w-md' => ! $projectId])>
        <header class="flex shrink-0 items-center gap-3 px-5 pt-5 pb-3">
            @if ($thread)
                <x-filament::icon-button wire:click="openThread" icon="heroicon-m-arrow-left" color="gray" label="All conversations and activity" />
            @else
                <x-filament::icon icon="heroicon-o-chat-bubble-left-right" class="size-5 shrink-0 text-primary-500" />
            @endif
            <div class="min-w-0 flex-1">
                <h2 class="truncate text-sm font-semibold text-gray-950 dark:text-white" title="{{ $pageLabel }}">{{ $pageLabel }}</h2>
                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $thread ? 'Conversation' : ($projectId ? 'Activity' : 'Conversations & activity') }}</p>
            </div>
            @if ($projectId && ! $thread)
                <x-filament::icon-button wire:click="catchUpProject" icon="heroicon-m-sparkles" color="gray" label="Catch me up" wire:loading.attr="disabled" />
            @endif
            @if ($thread)
                <x-filament::icon-button wire:click="toggleResolved" icon="heroicon-m-check" :color="$thread->resolved_at ? 'success' : 'gray'" :label="$thread->resolved_at ? 'Reopen thread' : 'Resolve thread'" />
                <x-filament::dropdown placement="bottom-end">
                    <x-slot name="trigger"><x-filament::icon-button icon="heroicon-m-ellipsis-horizontal" color="gray" label="Conversation options" /></x-slot>
                    <x-filament::dropdown.list>
                        <x-filament::dropdown.list.item icon="heroicon-m-link" x-data="{ copied: false }" x-on:click="navigator.clipboard.writeText(new URL(@js($page . '?chat=' . $thread->id), location.origin).href).then(() => { copied = true })"><span x-text="copied ? 'Copied!' : 'Copy link'">Copy link</span></x-filament::dropdown.list.item>
                    </x-filament::dropdown.list>
                </x-filament::dropdown>
            @endif
            <x-filament::icon-button icon="heroicon-m-x-mark" color="gray" label="Close page chat" x-on:click="open = false" />
        </header>

        @if ($thread)
            <div class="flex shrink-0 items-center gap-2 border-b border-gray-100 px-5 pb-3 dark:border-white/5">
                @if ($thread->has_agent)
                    <button type="button" wire:click="toggleAgentPaused" @disabled($thread->resolved_at) aria-label="{{ $thread->agent_paused ? 'Resume agent' : 'Pause agent' }}" title="{{ $thread->agent_paused ? 'Mention @Agent or click to resume' : 'Agent is participating. Click to pause automatic replies' }}" class="inline-flex items-center gap-2 rounded-lg bg-purple-50 px-2.5 py-1.5 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-100 transition hover:bg-purple-100 disabled:opacity-60 dark:bg-purple-400/10 dark:text-purple-300 dark:ring-purple-400/15 dark:hover:bg-purple-400/20">
                        <x-filament::icon icon="heroicon-m-sparkles" class="size-3.5" />
                        {{ $thread->agent_paused ? 'Agent paused' : 'Agent active' }}
                        <x-filament::icon :icon="$thread->agent_paused ? 'heroicon-m-play' : 'heroicon-m-pause'" class="size-3.5" />
                    </button>
                @else
                    <p class="text-xs text-gray-400">Mention @Agent to invite it to this thread.</p>
                @endif
                @if ($thread->resolved_at)<span class="text-xs font-medium text-success-600 dark:text-success-400">Resolved</span>@endif
            </div>
        @elseif ($projectId)
            <nav aria-label="Activity filters" class="flex shrink-0 gap-1 border-b border-gray-100 px-5 pb-3 dark:border-white/5">
                @foreach (['all' => 'All', 'discussion' => 'Discussion', 'changes' => 'Changes'] as $filter => $label)
                    <button type="button" wire:click="filterFeed('{{ $filter }}')" aria-pressed="{{ $feedFilter === $filter ? 'true' : 'false' }}" @class(['rounded-lg px-3 py-2 text-xs font-medium transition', 'bg-gray-100 text-gray-950 dark:bg-white/10 dark:text-white' => $feedFilter === $filter, 'text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-white/5' => $feedFilter !== $filter])>{{ $label }}</button>
                @endforeach
                @if ($feedFilter !== 'changes')
                    <x-filament::dropdown placement="bottom-end">
                        <x-slot name="trigger"><x-filament::icon-button icon="heroicon-m-adjustments-horizontal" color="gray" label="Activity options" /></x-slot>
                        <x-filament::dropdown.list>
                            <label class="flex items-center gap-2 px-3 py-2 text-xs text-gray-500"><input type="checkbox" wire:model.live="showResolved" class="rounded border-gray-300 text-primary-600" /> Include resolved conversations</label>
                        </x-filament::dropdown.list>
                    </x-filament::dropdown>
                @endif
            </nav>
        @else
            <label class="flex items-center gap-2 border-b border-gray-100 px-5 py-3 text-xs text-gray-500 dark:border-white/5 dark:text-gray-400"><input type="checkbox" wire:model.live="showResolved" class="rounded border-gray-300 text-primary-600" /> Include resolved conversations</label>
        @endif

        <div class="min-h-0 flex-1 space-y-5 overflow-y-auto overscroll-contain px-5 py-5" x-ref="feed" data-chat-feed
            x-on:scroll.passive="following = $el.scrollHeight - $el.scrollTop - $el.clientHeight < 64; if (following) hasNewMessages = false"
            x-on:page-chat-scroll.window="if ($event.detail.room === @js($room)) feedChanged($event.detail.force ?? false)">
            @if ($hasMore)
                <div class="text-center"><x-filament::button x-on:click="const height = $refs.feed.scrollHeight; await $wire.loadMore(); $nextTick(() => $refs.feed.scrollTop += $refs.feed.scrollHeight - height)" color="gray" size="sm">Load earlier</x-filament::button></div>
            @endif
            @if ($thread)
                @include('filament.chat.message', ['message' => $thread])
                <div class="border-t border-gray-100 pt-4 text-xs font-medium text-gray-400 dark:border-white/10">Replies</div>
            @endif

            @php
                $previousMessage = null;
                $firstUnreadId = $entries->first(fn ($entry) => $entry instanceof \App\Models\PageMessage && in_array($entry->id, $unreadIds))?->id;
            @endphp
            @forelse ($entries as $entry)
                @if ($entry instanceof \App\Models\PageMessage && $entry->id === $firstUnreadId)
                    <div data-chat-unread-divider class="flex items-center gap-3 text-xs font-medium text-primary-600 dark:text-primary-400"><span class="h-px flex-1 bg-primary-100 dark:bg-primary-400/20"></span>New messages<span class="h-px flex-1 bg-primary-100 dark:bg-primary-400/20"></span></div>
                @endif
                @if ($entry instanceof \App\Models\HR\ProjectActivity)
                    <div wire:key="activity-entry-{{ $entry->id }}">
                        @include('filament.chat.activity', ['activity' => $entry])
                    </div>
                @else
                    @php
                        $grouped = $thread && $previousMessage instanceof \App\Models\PageMessage && ! $previousMessage->activity_id && ! $entry->activity_id && $previousMessage->body && $entry->body && $previousMessage->user_id === $entry->user_id && $previousMessage->is_agent === $entry->is_agent && $previousMessage->created_at->diffInMinutes($entry->created_at) < 5 && $entry->id !== $firstUnreadId;
                    @endphp
                    @include('filament.chat.message', ['message' => $entry, 'grouped' => $grouped])
                @endif
                @php $previousMessage = $entry; @endphp
            @empty
                <div class="py-12 text-center">
                    <x-filament::icon icon="heroicon-o-chat-bubble-oval-left-ellipsis" class="mx-auto mb-3 size-9 text-gray-300 dark:text-gray-600" />
                    <h3 class="text-sm font-medium text-gray-950 dark:text-white">{{ $thread ? 'Keep the conversation going' : 'A place to talk about this page' }}</h3>
                    <p class="mt-2 text-sm text-gray-500">{{ $thread ? 'Be the first to reply.' : 'Share a question, an update, or a little context.' }}</p>
                </div>
            @endforelse
        </div>

        <div x-cloak x-show="hasNewMessages" class="relative z-10 flex justify-center">
            <button type="button" x-on:click="jumpToLatest()" class="absolute bottom-3 inline-flex items-center gap-2 rounded-full bg-primary-600 px-4 py-2 text-xs font-medium text-white shadow-lg"><x-filament::icon icon="heroicon-m-arrow-down" class="size-4" /> New messages</button>
        </div>
        @if ($thread?->resolved_at)
            <div class="border-t border-gray-200 bg-success-50 px-5 py-5 text-sm text-success-700 dark:border-white/10 dark:bg-success-400/10 dark:text-success-300">This conversation is resolved. Reopen it to continue the discussion.</div>
        @else
        <form wire:submit="send" x-show="! @js((bool) $projectId) || $wire.threadId || $wire.feedFilter !== 'changes'" x-on:keydown="if ($event.key === 'Enter' && ($event.metaKey || $event.ctrlKey) && ! $event.isComposing) { $event.preventDefault(); $event.stopPropagation(); $el.requestSubmit() }" @class(['project-chat-composer' => $projectId, 'page-chat-composer shrink-0 border-t border-gray-100 p-4 dark:border-white/10'])>
            @if ($projectField)<p class="mb-2 text-xs font-medium text-primary-600 dark:text-primary-400">Discussing {{ str($projectField)->replace('_', ' ') }}</p>@endif
            {{ $this->form }}
            <div class="mt-3 flex items-center justify-between gap-3">
                <span class="text-xs text-gray-400"><span x-text="/Mac|iPhone|iPad/.test(navigator.platform) ? '⌘' : 'Ctrl'"></span> + Enter to send</span>
                <x-filament::icon-button type="submit" icon="heroicon-m-paper-airplane" label="Send" wire:loading.attr="disabled" wire:target="send" />
            </div>
        </form>
        @endif
    </aside>
    <x-filament-actions::modals />
</div>

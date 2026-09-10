<div x-data="{ open: $wire.entangle('isOpen').live }" x-on:keydown.escape.window="open = false">
    <div x-show="! open" class="fixed end-6 bottom-6 z-40">
        <x-filament::button icon="heroicon-o-chat-bubble-left-right" x-on:click="open = true" aria-label="Open page chat">Page chat @if ($unreadCount)<span class="ms-2 rounded-full bg-white/20 px-2 text-xs">{{ $unreadCount }}</span>@endif</x-filament::button>
    </div>

    <aside x-cloak x-show="open" x-transition.opacity aria-label="Page chat" class="fixed inset-y-0 end-0 z-40 flex w-full max-w-md flex-col border-s border-gray-200 bg-white shadow-2xl dark:border-white/10 dark:bg-gray-900">
        <header class="flex items-center gap-3 border-b border-gray-200 px-5 py-4 dark:border-white/10">
            <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600 dark:bg-primary-400/10 dark:text-primary-400">
                <x-filament::icon icon="heroicon-o-chat-bubble-left-right" class="size-5" />
            </div>
            <div class="min-w-0 flex-1">
                <h2 class="font-semibold text-gray-950 dark:text-white">{{ $thread ? 'Conversation' : 'Page chat' }}</h2>
                <p class="truncate text-xs text-gray-500 dark:text-gray-400" title="{{ $page }}">{{ $page }}</p>
            </div>
            <x-filament::icon-button icon="heroicon-m-x-mark" color="gray" label="Close page chat" x-on:click="open = false" />
        </header>

        @if ($thread)
            <button type="button" wire:click="openThread" class="flex items-center gap-2 border-b border-gray-100 px-5 py-3 text-sm font-medium text-primary-600 dark:border-white/5 dark:text-primary-400">
                <x-filament::icon icon="heroicon-m-arrow-left" class="size-4" /> All conversations & activity
            </button>
            <div class="flex items-center justify-between gap-3 border-b border-gray-100 px-5 py-3 dark:border-white/5">
                <x-filament::button wire:click="toggleResolved" :color="$thread->resolved_at ? 'gray' : 'success'" size="xs" icon="heroicon-m-check">{{ $thread->resolved_at ? 'Reopen thread' : 'Resolve thread' }}</x-filament::button>
                <button type="button" x-data="{ copied: false }" x-on:click="navigator.clipboard.writeText(new URL(@js($page . '?chat=' . $thread->id), location.origin).href).then(() => { copied = true; setTimeout(() => copied = false, 2000) })" class="text-xs font-medium text-primary-600 dark:text-primary-400" x-text="copied ? 'Copied!' : 'Copy link'">Copy link</button>
            </div>
        @else
            <label class="flex items-center gap-2 border-b border-gray-100 px-5 py-3 text-xs text-gray-500 dark:border-white/5 dark:text-gray-400"><input type="checkbox" wire:model.live="showResolved" class="rounded border-gray-300 text-primary-600" /> Include resolved conversations</label>
        @endif

        <div class="min-h-0 flex-1 space-y-5 overflow-y-auto overscroll-contain px-5 py-5" x-ref="feed"
            x-effect="if (open) $nextTick(() => $refs.feed.scrollTop = $refs.feed.scrollHeight)"
            x-on:page-chat-scroll.window="if ($event.detail.room === @js($room)) $nextTick(() => $refs.feed.scrollTop = $refs.feed.scrollHeight)">
            @if ($hasMore)
                <div class="text-center"><x-filament::button wire:click="loadMore" color="gray" size="sm">Load earlier</x-filament::button></div>
            @endif
            @if ($thread)
                @include('filament.chat.message', ['message' => $thread])
                <div class="border-t border-gray-100 pt-4 text-xs font-medium text-gray-400 dark:border-white/10">Replies</div>
            @endif

            @forelse ($entries as $entry)
                @if ($entry instanceof \App\Models\HR\ProjectActivity)
                    <div wire:key="activity-entry-{{ $entry->id }}">
                        @include('filament.chat.activity', ['activity' => $entry])
                        <button type="button" wire:click="replyToActivity({{ $entry->id }})" class="mt-2 text-xs font-medium text-primary-600 dark:text-primary-400">Discuss this change</button>
                    </div>
                @else
                    @include('filament.chat.message', ['message' => $entry])
                @endif
            @empty
                <div class="py-12 text-center">
                    <x-filament::icon icon="heroicon-o-chat-bubble-oval-left-ellipsis" class="mx-auto mb-3 size-9 text-gray-300 dark:text-gray-600" />
                    <h3 class="text-sm font-medium text-gray-950 dark:text-white">{{ $thread ? 'Keep the conversation going' : 'A place to talk about this page' }}</h3>
                    <p class="mt-2 text-sm text-gray-500">{{ $thread ? 'Be the first to reply.' : 'Share a question, an update, or a little context.' }}</p>
                </div>
            @endforelse
        </div>

        @if ($thread?->resolved_at)
            <div class="border-t border-gray-200 bg-success-50 px-5 py-5 text-sm text-success-700 dark:border-white/10 dark:bg-success-400/10 dark:text-success-300">This conversation is resolved. Reopen it to continue the discussion.</div>
        @else
        <form wire:submit="send" class="page-chat-composer border-t border-gray-200 p-4 dark:border-white/10">
            {{ $this->form }}
            <div class="mt-3 flex items-center justify-between gap-3">
                <span class="text-xs text-gray-400">Type @ to mention someone</span>
                <x-filament::button type="submit" size="sm" icon="heroicon-m-paper-airplane" wire:loading.attr="disabled" wire:target="send">Send</x-filament::button>
            </div>
        </form>
        @endif
    </aside>
    <x-filament-actions::modals />
</div>

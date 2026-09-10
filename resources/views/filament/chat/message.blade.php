<article wire:key="chat-message-{{ $message->id }}" class="flex gap-3" aria-label="{{ $message->activity ? 'Change by ' . $message->activity->actor_name : 'Message from ' . ($message->user?->name ?? 'Former user') }}">
    @if (! $message->activity_id)<div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-primary-50 text-xs font-semibold text-primary-700 dark:bg-primary-400/10 dark:text-primary-300">{{ str($message->user?->name ?? '?')->substr(0, 1)->upper() }}</div>@endif
    <div class="min-w-0 flex-1">
        @if (in_array($message->id, $unreadIds))<div class="mb-2 border-t border-primary-200 pt-1 text-xs font-medium text-primary-600 dark:border-primary-400/30 dark:text-primary-400">New message</div>@endif
        @if ($message->activity)
            @include('filament.chat.activity', ['activity' => $message->activity])
        @else
        <div class="flex flex-wrap items-baseline gap-x-2 gap-y-1">
            <span class="text-sm font-semibold text-gray-950 dark:text-white">{{ $message->user?->name ?? 'Former user' }}</span>
            <time class="text-xs text-gray-400" title="{{ $message->created_at }}">{{ $message->created_at->format('M j, H:i') }}</time>
            @if ($message->edited_at && $message->body)<span class="text-xs text-gray-400">edited</span>@endif
        </div>
        @if ($editingId === $message->id && $message->body !== null)
            <form wire:submit="saveEdit" class="mt-2 space-y-2">
                {{ $this->editForm }}
                <div class="flex gap-2"><x-filament::button type="submit" size="xs">Save edit</x-filament::button><x-filament::button wire:click="cancelEdit" color="gray" size="xs">Cancel</x-filament::button></div>
            </form>
        @else
            @if ($message->body === null)
                <p class="mt-1 text-sm text-gray-400 italic">Message deleted</p>
            @else
                <div class="fi-prose mt-1 text-sm leading-relaxed break-words">{!! $message->contentHtml() !!}</div>
            @endif
        @endif
        @endif
        <div class="mt-2 flex items-center gap-3 text-xs">
            @if ($message->resolved_at)<span class="font-medium text-success-600 dark:text-success-400">Resolved</span>@endif
            @if (! $threadId)
                <button type="button" wire:click="openThread({{ $message->id }})" class="font-medium text-primary-600 hover:underline dark:text-primary-400">{{ $message->replies_count ? $message->replies_count . ' ' . str('reply')->plural($message->replies_count) : 'Reply' }}</button>
                @if ($message->unread_replies_count)<span class="rounded-full bg-primary-50 px-2 py-0.5 font-medium text-primary-600 dark:bg-primary-400/10 dark:text-primary-400">{{ $message->unread_replies_count }} unread</span>@endif
            @endif
            @if ($message->user_id === auth()->id() && $message->body !== null && $editingId !== $message->id)
                <button type="button" wire:click="edit({{ $message->id }})" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">Edit</button>
                <span x-data="{ confirming: false }" class="inline-flex gap-2">
                    <button type="button" x-show="! confirming" x-on:click="confirming = true" class="text-gray-400 hover:text-danger-600">Delete</button>
                    <span x-cloak x-show="confirming" class="inline-flex gap-2">
                        <button type="button" wire:click="deleteMessage({{ $message->id }})" class="font-medium text-danger-600">Confirm delete</button>
                        <button type="button" x-on:click="confirming = false" class="text-gray-500">Cancel</button>
                    </span>
                </span>
            @endif
        </div>
    </div>
</article>

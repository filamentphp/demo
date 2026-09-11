<article wire:key="chat-message-{{ $message->id }}" class="page-chat-message flex gap-3 {{ ($grouped ?? false) ? '!-mt-3' : '' }}" aria-label="{{ $message->activity ? 'Change by ' . $message->activity->actor_name : 'Message from ' . $message->authorName() }}">
    @if (! $message->activity_id)<div aria-hidden="true" class="flex size-8 shrink-0 items-center justify-center rounded-full {{ ($grouped ?? false) ? 'invisible' : '' }} {{ $message->is_agent ? 'bg-purple-50 text-purple-700 dark:bg-purple-400/10 dark:text-purple-300' : 'bg-primary-50 text-primary-700 dark:bg-primary-400/10 dark:text-primary-300' }} text-xs font-semibold">{{ $message->is_agent ? 'AI' : str($message->authorName())->substr(0, 1)->upper() }}</div>@endif
    <div class="min-w-0 flex-1">
        @if ($projectId && $message->project_field)
            @if ($message->project_field === 'description')
            <button type="button" x-on:click="$dispatch('project-focus-field', { field: @js($message->project_field) }); open = false" class="mb-2 inline-flex items-center gap-1 rounded-md bg-primary-50 px-2 py-1 text-xs font-medium text-primary-700 dark:bg-primary-400/10 dark:text-primary-300">
                <x-filament::icon icon="heroicon-m-arrow-up-right" class="size-3" /> {{ str($message->project_field)->replace('_', ' ')->ucfirst() }}
            </button>
            @else
                <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">About {{ str($message->project_field)->replace('_', ' ') }}</p>
            @endif
        @endif
        @if ($message->activity)
            @include('filament.chat.activity', ['activity' => $message->activity])
        @else
        <div class="{{ ($grouped ?? false) ? 'sr-only' : 'flex flex-wrap items-baseline gap-x-2 gap-y-1' }}">
            <span class="text-sm font-semibold text-gray-950 dark:text-white">{{ $message->authorName() }}</span>
            @if ($message->agent_status === 'pending' || $message->agent_status === 'running')<span class="text-xs text-gray-400">working</span>@endif
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
            @elseif (in_array($message->agent_status, ['pending', 'running']))
                <div x-data="{ text: '' }"
                    x-on:page-agent-stream.window="if ($event.detail.room === @js($room) && $event.detail.messageId === {{ $message->id }} && $event.detail.text.length > text.length) { text = $event.detail.text; $dispatch('page-chat-scroll', { room: @js($room) }) }"
                    class="mt-1 text-sm leading-relaxed whitespace-pre-wrap break-words" x-text="text || 'Thinking…'">Thinking…</div>
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
            @if (! $message->is_agent && $message->user_id === auth()->id() && $message->body !== null && $editingId !== $message->id)
                <x-filament::dropdown placement="bottom-end" class="page-chat-message-actions ms-auto">
                    <x-slot name="trigger"><x-filament::icon-button icon="heroicon-m-ellipsis-horizontal" color="gray" label="Message options" size="sm" /></x-slot>
                    <x-filament::dropdown.list>
                        <x-filament::dropdown.list.item wire:click="edit({{ $message->id }})" icon="heroicon-m-pencil-square">Edit</x-filament::dropdown.list.item>
                        <x-filament::dropdown.list.item wire:click="mountAction('deleteMessage', { message: {{ $message->id }} })" icon="heroicon-m-trash" color="danger">Delete</x-filament::dropdown.list.item>
                    </x-filament::dropdown.list>
                </x-filament::dropdown>
            @endif
        </div>
    </div>
</article>

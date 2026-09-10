<div class="mx-auto w-full max-w-4xl" aria-label="Project history">
    <div class="mb-8">
        <h3 class="text-base font-semibold text-gray-950 dark:text-white">Project history</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Saved changes and task milestones, newest first.</p>
    </div>

    @if ($activities->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-300 px-6 py-12 text-center dark:border-gray-700">
            <x-filament::icon icon="heroicon-o-clock" class="mx-auto mb-3 size-8 text-gray-400" />
            <h4 class="font-semibold text-gray-950 dark:text-white">The story starts here</h4>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                {{ $record ? 'New saved project changes and task additions or deletions will appear here. Earlier activity has not been backfilled.' : 'Save this project to start its history.' }}
            </p>
        </div>
    @else
        <ol class="space-y-0">
            @foreach ($activities as $activity)
                @php
                    [$title, $icon, $color] = match ($activity->event) {
                        'project_created' => ['Project created', 'heroicon-m-sparkles', 'success'],
                        'project_deleted' => ['Project archived', 'heroicon-m-archive-box', 'danger'],
                        'project_restored' => ['Project restored', 'heroicon-m-arrow-uturn-left', 'success'],
                        'task_created' => ['Task added', 'heroicon-m-plus', 'success'],
                        'task_deleted' => ['Task deleted', 'heroicon-m-trash', 'danger'],
                        default => ['Project updated', 'heroicon-m-pencil-square', 'primary'],
                    };
                @endphp
                <li wire:key="activity-{{ $activity->id }}" class="relative flex gap-4 pb-8 last:pb-0">
                    @if (! $loop->last)
                        <div class="absolute start-5 top-10 bottom-0 w-px bg-gray-200 dark:bg-gray-700" aria-hidden="true"></div>
                    @endif
                    <div class="relative flex size-10 shrink-0 items-center justify-center rounded-full bg-gray-100 ring-4 ring-white dark:bg-gray-800 dark:ring-gray-900">
                        <x-filament::icon :icon="$icon" class="size-5 text-gray-500 dark:text-gray-400" />
                    </div>
                    <div class="min-w-0 flex-1 rounded-xl bg-gray-50 p-4 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <x-filament::badge :color="$color">{{ $title }}</x-filament::badge>
                            <time datetime="{{ $activity->updated_at->toIso8601String() }}" class="text-xs text-gray-500 dark:text-gray-400" title="{{ $activity->updated_at->format('j M Y, H:i:s T') }}">
                                {{ $activity->updated_at->format('j M Y, H:i:s T') }}
                            </time>
                        </div>
                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-medium text-gray-950 dark:text-white">{{ $activity->actor_name }}</span>
                            <span aria-hidden="true"> · </span>
                            {{ match ($activity->interface) { 'client_portal' => 'Client portal', 'panel' => 'Panel', default => 'System' } }}
                        </p>
                        @if ($activity->subject)
                            <p class="mt-3 text-sm font-medium break-words text-gray-950 dark:text-white">{{ $activity->subject }}</p>
                        @endif
                        @if ($activity->changes)
                            <dl class="mt-4 space-y-3 border-t border-gray-200 pt-4 text-sm dark:border-gray-700">
                                @foreach ($activity->changes as $field => $change)
                                    <div class="grid gap-1 sm:grid-cols-[8rem_1fr] sm:gap-4">
                                        <dt class="font-medium text-gray-600 dark:text-gray-300">{{ $field === 'department_id' ? 'Department' : str($field)->replace('_', ' ')->ucfirst() }}</dt>
                                        <dd class="min-w-0 break-words text-gray-950 dark:text-white">
                                            @if (! in_array($field, ['description', 'plan']))
                                                <span class="text-gray-500 line-through dark:text-gray-400">{{ $change['old'] ?? 'Not set' }}</span>
                                                <span class="mx-1 text-gray-400" aria-label="changed to">→</span>
                                            @endif
                                            <span>{{ $change['new'] ?? 'Not set' }}</span>
                                        </dd>
                                    </div>
                                @endforeach
                            </dl>
                        @endif
                    </div>
                </li>
            @endforeach
        </ol>
        @if ($hasMore)
            <div class="mt-6 text-center">
                <x-filament::button wire:click="loadMore" color="gray" size="sm">Load older activity</x-filament::button>
            </div>
        @endif
    @endif
</div>

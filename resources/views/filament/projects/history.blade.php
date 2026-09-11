<div class="w-full" aria-label="Project history">
    <div class="mb-6">
        <h3 class="text-base font-semibold text-gray-950 dark:text-white">Project history</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Saved changes and task milestones, newest first.</p>
    </div>

    @if ($activities->isEmpty())
        <div class="rounded-xl bg-gray-50/70 px-6 py-10 text-center dark:bg-white/5">
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
                        'revision_applied' => ['Revision applied', 'heroicon-m-document-check', 'success'],
                        default => ['Project updated', 'heroicon-m-pencil-square', 'primary'],
                    };
                @endphp
                <li wire:key="activity-{{ $activity->id }}" class="relative flex gap-3 pb-6 last:pb-0">
                    @if (! $loop->last)
                        <div class="absolute start-3.5 top-8 bottom-1 w-px bg-gray-200 dark:bg-white/10" aria-hidden="true"></div>
                    @endif
                    <div class="relative flex size-7 shrink-0 items-center justify-center rounded-full bg-gray-100 dark:bg-white/5">
                        <x-filament::icon :icon="$icon" class="size-3.5 text-gray-500 dark:text-gray-400" />
                    </div>
                    <details class="group min-w-0 flex-1 pt-0.5" @if (count($activity->changes ?? []) === 1) open @endif>
                        <summary class="cursor-pointer list-none rounded-md text-sm focus-visible:outline-2 focus-visible:outline-primary-500 [&::-webkit-details-marker]:hidden">
                        <div class="flex flex-wrap items-baseline gap-x-2 gap-y-1">
                            <span class="font-medium text-gray-950 dark:text-white">{{ $activity->actor_name }}</span>
                            <span class="text-gray-500 dark:text-gray-400" title="{{ $title }}">{{ match ($activity->event) { 'project_created' => 'created the project', 'project_deleted' => 'archived the project', 'project_restored' => 'restored the project', 'task_created' => 'added a task', 'task_deleted' => 'deleted a task', 'revision_applied' => 'applied a revision', default => 'updated the project' } }}</span>
                            <time datetime="{{ $activity->updated_at->toIso8601String() }}" class="text-xs text-gray-500 dark:text-gray-400" title="{{ $activity->updated_at->format('j M Y, H:i:s T') }}">
                                {{ $activity->updated_at->format('M j, H:i') }}
                            </time>
                            <x-filament::icon icon="heroicon-m-chevron-right" class="ms-auto size-3.5 shrink-0 text-gray-400 transition group-open:rotate-90" />
                        </div>
                        @if ($activity->changes)
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ collect(array_keys($activity->changes))->map(fn ($field) => $field === 'department_id' ? 'Department' : str($field)->replace('_', ' ')->ucfirst())->join(', ') }}</p>
                        @endif
                        @if ($activity->subject)
                            <p class="mt-1 text-sm break-words text-gray-700 dark:text-gray-300">{{ $activity->subject }}</p>
                        @endif
                        </summary>
                        <div class="mt-3 rounded-lg bg-gray-50 px-3 py-3 dark:bg-white/5">
                        @if ($activity->revision_id)
                            <a class="mb-3 inline-flex text-sm font-medium text-primary-600 dark:text-primary-400" href="{{ \App\Filament\Resources\HR\Projects\ProjectResource::getUrl('view', ['record' => $record, 'project-tab' => 'project-revisions']) }}#revision-{{ $activity->revision_id }}">Review this decision and discussion →</a>
                        @endif
                        @if ($activity->changes)
                            <dl class="space-y-3 text-sm">
                                @foreach ($activity->changes as $field => $change)
                                    <div class="grid gap-2 rounded-lg px-2 py-1.5 transition hover:bg-white/80 dark:hover:bg-white/5 sm:grid-cols-[8rem_1fr_auto] sm:items-center sm:gap-4">
                                        <dt class="font-medium text-gray-600 dark:text-gray-300">{{ $field === 'department_id' ? 'Department' : str($field)->replace('_', ' ')->ucfirst() }}</dt>
                                        <dd class="flex min-w-0 flex-wrap items-center gap-2 break-words text-gray-950 dark:text-white">
                                            @if (in_array($field, ['status', 'priority']))
                                                <x-filament::badge color="gray">{{ $change['old'] ?? 'Not set' }}</x-filament::badge>
                                                <span class="text-gray-400" aria-label="changed to">→</span>
                                                <x-filament::badge :color="match (strtolower($change['new'] ?? '')) { 'active' => 'success', 'completed' => 'info', 'on hold', 'high' => 'warning', 'cancelled', 'critical' => 'danger', default => 'gray' }">{{ $change['new'] ?? 'Not set' }}</x-filament::badge>
                                            @else
                                            @if (! in_array($field, ['description', 'plan']))
                                                <span class="text-gray-500 line-through dark:text-gray-400">{{ $change['old'] ?? 'Not set' }}</span>
                                                <span class="mx-1 text-gray-400" aria-label="changed to">→</span>
                                            @endif
                                            <span>{{ $change['new'] ?? 'Not set' }}</span>
                                            @endif
                                        </dd>
                                        @if (isset($activity->raw_changes[$field]) && array_key_exists('old', $activity->raw_changes[$field]) && array_key_exists('new', $activity->raw_changes[$field]))
                                            <div class="justify-self-start sm:justify-self-end">
                                                {{ ($this->restoreField)(['activity' => $activity->id, 'field' => $field]) }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </dl>
                        @endif
                        <p class="{{ $activity->changes ? 'mt-3 border-t border-gray-200 pt-2 dark:border-white/10' : '' }} text-xs text-gray-500 dark:text-gray-400">
                            {{ match ($activity->interface) { 'client_portal' => 'Client portal', 'panel' => 'Panel', default => 'System' } }} · {{ $activity->updated_at->format('j M Y, H:i:s T') }}
                        </p>
                        </div>
                    </details>
                </li>
            @endforeach
        </ol>
        @if ($hasMore)
            <div class="mt-6 text-center">
                <x-filament::button wire:click="loadMore" color="gray" size="sm">Load older activity</x-filament::button>
            </div>
        @endif
    @endif
    <x-filament-actions::modals />
</div>

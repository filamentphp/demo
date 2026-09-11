<div class="space-y-4">
    @php
        $openRevision = $revisions->first(fn ($revision) => in_array($revision->status, ['pending', 'changes_requested'], true));
    @endphp

    @if ($errors->any())
        <div role="alert" class="rounded-xl bg-danger-50 p-4 text-sm text-danger-700 dark:bg-danger-400/10 dark:text-danger-300">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="flex flex-wrap items-center justify-between gap-3">
        <h3 class="text-base font-semibold text-gray-950 dark:text-white">Project revisions</h3>
        @if (! $openRevision)
            {{ $this->proposeAction }}
        @endif
    </div>

    @forelse ($revisions as $revision)
        @php
            $isOpen = in_array($revision->status, ['pending', 'changes_requested'], true);
            $isAuthor = auth()->id() === $revision->author_id;
            $isReviewer = auth()->id() === $revision->requested_reviewer_id;
            $staleFields = $isOpen ? $revision->staleFields() : [];
            $arguments = ['revision' => $revision->id, 'version' => $revision->version];
            $beforeValues = $revision->base_values;
            $afterValues = $revision->proposed_values;
            foreach ($revision->proposed_tasks ?? [] as $taskId => $fields) {
                foreach ($fields as $field => $value) {
                    $beforeValues["task_{$taskId}_{$field}"] = $revision->base_tasks[$taskId][$field] ?? null;
                    $afterValues["task_{$taskId}_{$field}"] = $value;
                }
            }
        @endphp

        <details id="revision-{{ $revision->id }}" wire:key="project-revision-{{ $revision->id }}" @if ($isOpen) open @endif class="rounded-xl border border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900">
            <summary class="cursor-pointer rounded-xl px-4 py-3 focus-visible:outline-2 focus-visible:outline-primary-600">
                <span class="font-semibold text-gray-950 dark:text-white">{{ $this->proposalTitle($revision) }}</span>
                <span class="mt-1 flex flex-wrap items-center gap-2">
                    <x-filament::badge :color="$staleFields ? 'warning' : $this->statusColor($revision->status)">
                        {{ $this->decisionStatus($revision, (bool) $staleFields) }}
                    </x-filament::badge>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $revision->author->name }} · {{ $revision->created_at->diffForHumans() }}</span>
                </span>
            </summary>

            <div class="space-y-4 px-4 pb-4">
                @if ($staleFields)
                    <p role="alert" class="rounded-lg bg-warning-50 px-3 py-2 text-sm font-medium text-warning-700 dark:bg-warning-400/10 dark:text-warning-300">Project changed. Resolve the marked conflicts before this proposal can be applied.</p>
                @endif

                <p class="whitespace-pre-line text-sm text-gray-700 dark:text-gray-200">{{ $revision->reason }}</p>

                @if ($revision->feedback)
                    <div class="border-s-2 border-warning-400 ps-3 text-sm text-gray-700 dark:text-gray-200">
                        <p class="font-medium text-warning-700 dark:text-warning-300">Reviewer feedback</p>
                        <p class="whitespace-pre-line">{{ $revision->feedback }}</p>
                    </div>
                @endif

                <div class="space-y-3">
                    @foreach ($afterValues as $field => $after)
                        @php($before = $beforeValues[$field] ?? null)
                        <div>
                            @if (array_key_exists($field, $staleFields))
                                <p class="mb-1 text-xs font-medium text-warning-700 dark:text-warning-300">{{ $this->fieldLabel($field) }} conflict · Current value differs from this baseline</p>
                            @endif
                            @if ($field === 'description')
                                <details class="rounded-lg border border-gray-200 dark:border-white/10">
                                    <summary class="cursor-pointer rounded-lg px-3 py-2 text-sm font-medium text-gray-950 focus-visible:outline-2 focus-visible:outline-primary-600 dark:text-white">Description comparison</summary>
                                    <div class="grid gap-4 border-t border-gray-100 p-3 md:grid-cols-2 dark:border-white/10">
                                        <div class="min-w-0">
                                            <p class="mb-2 text-xs font-medium text-gray-500 dark:text-gray-400">Before</p>
                                            <div class="fi-prose max-w-none text-sm">{!! $this->richValue($field, $before) !!}</div>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="mb-2 text-xs font-medium text-success-600 dark:text-success-400">Proposed</p>
                                            <div class="fi-prose max-w-none text-sm">{!! $this->richValue($field, $after) !!}</div>
                                        </div>
                                    </div>
                                </details>
                            @else
                                <p class="flex flex-wrap items-baseline gap-x-2 gap-y-1 break-words text-sm">
                                    <span class="font-medium text-gray-950 dark:text-white">{{ $this->fieldLabel($field) }}</span>
                                    <span class="text-gray-500 dark:text-gray-400"><span class="sr-only">Before: </span>{{ $this->plainValue($field, $before) }}</span>
                                    <span aria-hidden="true" class="text-gray-400">→</span>
                                    <span class="font-medium text-gray-950 dark:text-white"><span class="sr-only">Proposed: </span>{{ $this->plainValue($field, $after) }}</span>
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <footer class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($isOpen && $isAuthor)
                            @if ($staleFields)
                                {{ ($this->resolveStaleAction)($arguments) }}
                            @else
                                {{ ($this->reviseAction)($arguments) }}
                            @endif
                        @elseif ($isOpen && $isReviewer && $revision->status === 'pending')
                            @if (! $staleFields)
                                {{ ($this->approveAction)($arguments) }}
                            @endif
                            {{ ($this->requestChangesAction)($arguments)->outlined() }}
                        @endif

                        <x-filament::dropdown placement="bottom-end">
                            <x-slot name="trigger">
                                <x-filament::button color="gray" outlined icon="heroicon-m-ellipsis-horizontal" aria-label="More proposal actions">More</x-filament::button>
                            </x-slot>
                            <x-filament::dropdown.list>
                                {{ ($this->previewAction)($arguments)->grouped() }}
                                @if ($revision->status === 'applied' && ! $openRevision)
                                    {{ ($this->rollbackAction)($arguments)->grouped() }}
                                @endif
                                @if ($isOpen)
                                    @if ($isAuthor || $isReviewer)
                                        {{ ($this->requestReviewAction)($arguments)->label($revision->requestedReviewer ? 'Change reviewer' : 'Request review')->grouped() }}
                                    @endif
                                    @if ($isAuthor)
                                        @if ($staleFields)
                                            {{ ($this->reviseAction)($arguments)->grouped() }}
                                        @endif
                                        {{ ($this->withdrawAction)($arguments)->grouped() }}
                                    @elseif ($isReviewer)
                                        {{ ($this->rejectAction)($arguments)->grouped() }}
                                    @endif
                                @endif
                            </x-filament::dropdown.list>
                        </x-filament::dropdown>
                    </div>

                    <x-filament::button color="gray" size="sm" outlined icon="heroicon-m-chat-bubble-left-right" wire:click="openDiscussion({{ $revision->id }})">Open discussion</x-filament::button>
                </footer>
            </div>
        </details>
    @empty
        <p class="text-sm text-gray-500 dark:text-gray-400">No revisions yet.</p>
    @endforelse

    <x-filament-actions::modals />
</div>

<div class="space-y-6">
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

    @if ($record)
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-gray-200 bg-gray-50 px-5 py-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-3">
                <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-white text-primary-600 ring-1 ring-gray-200 dark:bg-gray-900 dark:text-primary-400 dark:ring-white/10">
                    <x-filament::icon icon="heroicon-o-user-circle" class="size-6" />
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Project owner</p>
                    <p class="text-sm font-semibold text-gray-950 dark:text-white">{{ $projectOwner?->name ?? 'Unassigned' }}</p>
                </div>
            </div>
            {{ $this->assignOwnerAction }}
        </div>
    @endif

    <div class="flex flex-wrap items-start justify-between gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-gray-900">
        <div class="max-w-2xl">
            <h3 class="text-base font-semibold text-gray-950 dark:text-white">Project revisions</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Suggest focused changes without editing the live project. A teammate reviews and applies the proposal.</p>
        </div>

        @if (! $openRevision)
            {{ $this->proposeAction }}
        @else
            <x-filament::badge color="gray">One proposal is already open</x-filament::badge>
        @endif
    </div>

    @forelse ($revisions as $revision)
        @php
            $isOpen = in_array($revision->status, ['pending', 'changes_requested'], true);
            $isAuthor = auth()->id() === $revision->author_id;
            $staleFields = $isOpen ? $revision->staleFields() : [];
            $beforeValues = $revision->base_values;
            $afterValues = $revision->proposed_values;
            foreach ($revision->proposed_tasks ?? [] as $taskId => $fields) {
                foreach ($fields as $field => $value) {
                    $beforeValues["task_{$taskId}_{$field}"] = $revision->base_tasks[$taskId][$field] ?? null;
                    $afterValues["task_{$taskId}_{$field}"] = $value;
                }
            }
        @endphp

        <article id="revision-{{ $revision->id }}" wire:key="project-revision-{{ $revision->id }}" class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
            <header class="flex flex-wrap items-start justify-between gap-4 border-b border-gray-100 px-5 py-4 dark:border-white/10">
                <div class="flex min-w-0 items-start gap-3">
                    <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary-50 text-sm font-semibold text-primary-700 dark:bg-primary-400/10 dark:text-primary-300">
                        {{ str($revision->author->name)->substr(0, 1)->upper() }}
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h4 class="font-semibold text-gray-950 dark:text-white">Revision #{{ $revision->id }}</h4>
                            <x-filament::badge :color="$this->statusColor($revision->status)">
                                {{ str($revision->status)->replace('_', ' ')->title() }}
                            </x-filament::badge>
                            @if ($staleFields)
                                <x-filament::badge color="warning" icon="heroicon-m-exclamation-triangle">Project changed</x-filament::badge>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Proposed by <span class="font-medium text-gray-700 dark:text-gray-200">{{ $revision->author->name }}</span>
                            <span aria-hidden="true">·</span> {{ $revision->created_at->diffForHumans() }}
                            <span aria-hidden="true">·</span> Version {{ $revision->version }}
                            @if ($revision->source_message_id)
                                <span aria-hidden="true">·</span> Prepared with Agent
                            @endif
                            @if ($revision->rollback_of_id)
                                <span aria-hidden="true">·</span> Rollback of #{{ $revision->rollback_of_id }}
                            @endif
                        </p>
                    </div>
                </div>

                <button type="button" wire:click="openDiscussion({{ $revision->id }})" class="inline-flex items-center gap-1.5 text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400">
                    <x-filament::icon icon="heroicon-m-chat-bubble-left-right" class="size-4" />
                    Open discussion
                </button>
            </header>

            <div class="space-y-5 p-5">
                @if ($isOpen)
                    <div class="flex flex-wrap items-center justify-between gap-4 rounded-lg bg-primary-50 px-4 py-3 dark:bg-primary-400/10">
                        <div class="flex items-center gap-3">
                            <x-filament::icon icon="heroicon-m-arrow-right-circle" class="size-5 shrink-0 text-primary-600 dark:text-primary-400" />
                            <div>
                                <p class="text-xs font-medium text-primary-600 dark:text-primary-400">Next step · {{ $revision->nextStepOwner()?->name }}</p>
                                <p class="mt-0.5 text-sm font-semibold text-primary-950 dark:text-primary-100">{{ $revision->nextStep() }}</p>
                                @if ($revision->requestedReviewer)
                                    <p class="mt-1 text-xs text-primary-700 dark:text-primary-300">Review requested from {{ $revision->requestedReviewer->name }} · {{ $revision->review_requested_at?->diffForHumans() }}</p>
                                @endif
                            </div>
                        </div>
                        @if ($isAuthor || auth()->id() === $revision->requested_reviewer_id)
                            {{ ($this->requestReviewAction)(['revision' => $revision->id, 'version' => $revision->version]) }}
                        @endif
                    </div>
                @endif

                <div class="rounded-lg bg-gray-50 px-4 py-3 dark:bg-white/5">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Reason</p>
                    <p class="mt-1 whitespace-pre-line text-sm text-gray-800 dark:text-gray-200">{{ $revision->reason }}</p>
                </div>

                @if ($revision->feedback)
                    <div class="rounded-lg border border-warning-200 bg-warning-50 px-4 py-3 dark:border-warning-400/20 dark:bg-warning-400/10">
                        <p class="text-xs font-medium uppercase tracking-wide text-warning-700 dark:text-warning-300">Reviewer feedback</p>
                        <p class="mt-1 whitespace-pre-line text-sm text-warning-900 dark:text-warning-100">{{ $revision->feedback }}</p>
                    </div>
                @endif

                <div class="space-y-3">
                    @foreach ($afterValues as $field => $after)
                        @php($before = $beforeValues[$field] ?? null)
                        <section class="overflow-hidden rounded-lg border border-gray-200 dark:border-white/10">
                            <div class="flex items-center justify-between gap-3 border-b border-gray-100 bg-gray-50 px-4 py-2 dark:border-white/10 dark:bg-white/5">
                                <h5 class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $this->fieldLabel($field) }}</h5>
                                @if (array_key_exists($field, $staleFields))
                                    <span class="text-xs font-medium text-warning-600 dark:text-warning-400">Current value differs from this baseline</span>
                                @endif
                            </div>
                            <div class="grid md:grid-cols-2">
                                <div class="min-w-0 border-b border-gray-100 p-4 md:border-e md:border-b-0 dark:border-white/10">
                                    <p class="mb-2 text-xs font-medium uppercase tracking-wide text-gray-400">Before</p>
                                    <div class="{{ $field === 'description' ? 'fi-prose max-w-none text-sm' : 'break-words text-sm text-gray-600 dark:text-gray-300' }}">
                                        {!! $this->richValue($field, $before) !!}
                                    </div>
                                </div>
                                <div class="min-w-0 bg-success-50/50 p-4 dark:bg-success-400/5">
                                    <p class="mb-2 text-xs font-medium uppercase tracking-wide text-success-600 dark:text-success-400">After</p>
                                    <div class="{{ $field === 'description' ? 'fi-prose max-w-none text-sm' : 'break-words text-sm font-medium text-gray-950 dark:text-white' }}">
                                        {!! $this->richValue($field, $after) !!}
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endforeach
                </div>

                <footer class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 pt-4 dark:border-white/10">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        @if ($revision->reviewer)
                            Reviewed by <span class="font-medium text-gray-700 dark:text-gray-200">{{ $revision->reviewer->name }}</span>
                        @elseif ($isOpen)
                            {{ $revision->requestedReviewer ? 'Requested reviewer: ' . $revision->requestedReviewer->name : 'No reviewer requested yet' }}
                        @else
                            Closed {{ $revision->updated_at->diffForHumans() }}
                        @endif
                    </p>

                    <div class="flex flex-wrap gap-2">
                        {{ ($this->previewAction)(['revision' => $revision->id]) }}
                        @if ($revision->status === 'applied' && ! $openRevision)
                            {{ ($this->rollbackAction)(['revision' => $revision->id]) }}
                        @endif
                        @if ($isOpen)
                            @if ($isAuthor)
                                @if ($staleFields)
                                    {{ ($this->resolveStaleAction)(['revision' => $revision->id, 'version' => $revision->version]) }}
                                @endif
                                {{ ($this->reviseAction)(['revision' => $revision->id, 'version' => $revision->version]) }}
                                {{ ($this->withdrawAction)(['revision' => $revision->id, 'version' => $revision->version]) }}
                            @elseif (auth()->id() === $revision->requested_reviewer_id)
                                @if ($revision->status === 'pending')
                                    {{ ($this->requestChangesAction)(['revision' => $revision->id, 'version' => $revision->version]) }}
                                @endif
                                {{ ($this->rejectAction)(['revision' => $revision->id, 'version' => $revision->version]) }}
                                @if (! $staleFields && $revision->status === 'pending')
                                    {{ ($this->approveAction)(['revision' => $revision->id, 'version' => $revision->version]) }}
                                @endif
                            @endif
                        @endif
                    </div>
                </footer>
            </div>
        </article>
    @empty
        <div class="rounded-xl border border-dashed border-gray-300 px-6 py-12 text-center dark:border-white/20">
            <x-filament::icon icon="heroicon-o-document-duplicate" class="mx-auto size-10 text-gray-400" />
            <h3 class="mt-3 text-sm font-semibold text-gray-950 dark:text-white">No revisions yet</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Proposals and their review history will appear here.</p>
        </div>
    @endforelse

    <x-filament-actions::modals />
</div>

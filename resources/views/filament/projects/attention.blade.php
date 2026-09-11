<x-filament-panels::page>
    @php
        $review = $this->revisionsAwaitingReview();
        $own = $this->ownRevisionsNeedingAttention();
        $mentions = $this->unreadMentions();
        $blocked = $this->blockedProjects();
    @endphp

    <div class="grid gap-6 xl:grid-cols-2">
        @foreach ([
            ['title' => 'Awaiting review', 'description' => 'Review requests assigned to you and ready for a decision.', 'icon' => 'heroicon-o-document-magnifying-glass', 'items' => $review, 'empty' => 'You have no outstanding review requests.', 'kind' => 'revision'],
            ['title' => 'Your revisions', 'description' => 'Proposals where you own the next step: choose a reviewer, revise or resolve changed values.', 'icon' => 'heroicon-o-pencil-square', 'items' => $own, 'empty' => 'Your open revisions do not need attention.', 'kind' => 'own'],
            ['title' => 'Unread mentions', 'description' => 'Unread, unresolved mentions in project conversations.', 'icon' => 'heroicon-o-at-symbol', 'items' => $mentions, 'empty' => 'You have no unread unresolved project mentions.', 'kind' => 'mention'],
            ['title' => 'Blocked projects', 'description' => 'Projects you own that are currently on hold.', 'icon' => 'heroicon-o-pause-circle', 'items' => $blocked, 'empty' => 'None of your projects are currently on hold.', 'kind' => 'blocked'],
        ] as $section)
            <x-filament::section :heading="$section['title']" :description="$section['description']" :icon="$section['icon']">
                @if ($section['items']->isEmpty())
                    <div class="rounded-xl bg-gray-50 px-5 py-8 text-center dark:bg-white/5">
                        <x-filament::icon icon="heroicon-o-check-circle" class="mx-auto size-7 text-gray-400" />
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $section['empty'] }}</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-200 dark:divide-white/10">
                        @foreach ($section['items'] as $item)
                            @php
                                $revision = in_array($section['kind'], ['revision', 'own']) ? $item : null;
                                $message = $section['kind'] === 'mention' ? $item['message'] : null;
                                $project = $revision?->project ?? ($message ? $item['project'] : $item);
                                $thread = $message ? ($message->parent_id ?? $message->id) : null;
                            @endphp
                            <div class="flex items-start justify-between gap-4 py-4 first:pt-0 last:pb-0">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-gray-950 dark:text-white">{{ $project->name }}</p>
                                    <p class="mt-1 line-clamp-2 text-sm text-gray-500 dark:text-gray-400">
                                        @if ($section['kind'] === 'revision') Proposed by {{ $revision->author->name }} · {{ $revision->reason }}
                                        @elseif ($section['kind'] === 'own') {{ $revision->status === 'changes_requested' ? 'Changes requested · ' : '' }}{{ $revision->nextStep() }}
                                        @elseif ($section['kind'] === 'mention') {{ $message->authorName() }} mentioned you
                                        @else On hold · updated {{ $project->updated_at->diffForHumans() }}
                                        @endif
                                    </p>
                                </div>
                                <x-filament::button tag="a" :href="$this->projectUrl($project, $thread, $revision?->id)" color="gray" size="sm">
                                    {{ $section['kind'] === 'mention' ? 'Open thread' : ($revision ? 'Review' : 'Open') }}
                                </x-filament::button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-filament::section>
        @endforeach
    </div>
</x-filament-panels::page>

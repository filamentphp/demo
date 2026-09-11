<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex gap-2" aria-label="Preview version">
            <x-filament::button wire:click="$set('previewMode', 'current')" :color="$this->previewMode === 'current' ? 'primary' : 'gray'">Current</x-filament::button>
            <x-filament::button wire:click="$set('previewMode', 'proposed')" :color="$this->previewMode === 'proposed' ? 'primary' : 'gray'">Proposed</x-filament::button>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">Read-only preview · nothing is applied here</p>
    </div>
    <div class="grid gap-3 sm:grid-cols-3">
        <div class="rounded-xl bg-gray-50 p-4 dark:bg-white/5"><p class="text-xs text-gray-500">Budget</p><p class="mt-1 text-xl font-semibold">${{ number_format((float) $project->budget) }}</p></div>
        <div class="rounded-xl bg-gray-50 p-4 dark:bg-white/5"><p class="text-xs text-gray-500">Remaining budget</p><p class="mt-1 text-xl font-semibold">${{ number_format((float) $project->budget - (float) $project->spent) }}</p></div>
        <div class="rounded-xl bg-gray-50 p-4 dark:bg-white/5"><p class="text-xs text-gray-500">Tasks completed</p><p class="mt-1 text-xl font-semibold">{{ $project->tasks->where('status', \App\Enums\TaskStatus::Completed)->count() }} / {{ $project->tasks->count() }}</p></div>
    </div>
    <div wire:key="revision-preview-{{ $this->previewRevisionId }}-{{ $this->previewMode }}">
        {{ $this->previewInfolist }}
    </div>
    <section class="space-y-3">
        <h3 class="font-semibold">Project tasks</h3>
        @forelse ($project->tasks as $task)
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-gray-200 p-3 text-sm dark:border-white/10">
                <span class="min-w-0 break-words font-medium">{{ $task->title }}</span>
                <div class="flex items-center gap-3">
                    <x-filament::badge :color="$task->status->getColor()">{{ $task->status->getLabel() }}</x-filament::badge>
                    <span class="text-gray-500 dark:text-gray-400">{{ $task->due_date?->format('M j, Y') ?? 'No due date' }}</span>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500">No tasks yet.</p>
        @endforelse
    </section>
</div>

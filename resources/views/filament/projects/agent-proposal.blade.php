<div class="space-y-4">
    @foreach ($this->agentProposal as $field => $change)
        <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10">
            <h3 class="mb-3 text-sm font-semibold text-gray-950 dark:text-white">{{ str($field)->replace('_', ' ')->ucfirst() }}</h3>
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/5"><p class="mb-1 text-xs text-gray-500">Your draft</p><p class="break-words text-sm">{{ $this->conflictValue($field, $change['before']) }}</p></div>
                <div class="rounded-lg bg-primary-50 p-3 dark:bg-primary-400/10"><p class="mb-1 text-xs text-primary-600 dark:text-primary-400">Proposed</p><p class="break-words text-sm">{{ $this->conflictValue($field, $change['proposed']) }}</p></div>
            </div>
        </div>
    @endforeach
</div>

<details wire:key="chat-activity-{{ $activity->id }}" class="border-s-2 border-gray-200 ps-3 py-1 text-xs text-gray-500 dark:border-white/10 dark:text-gray-400">
    <summary class="cursor-pointer leading-relaxed break-words">
        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $activity->actor_name }}</span>
        {{ match ($activity->event) { 'project_created' => 'created the project', 'project_deleted' => 'archived the project', 'project_restored' => 'restored the project', 'task_created' => 'added a task', 'task_deleted' => 'deleted a task', default => 'updated ' . collect(array_keys($activity->changes ?? []))->map(fn ($field) => $field === 'department_id' ? 'department' : str_replace('_', ' ', $field))->join(', ') } }}
        @if (count($activity->changes ?? []) === 1 && ! in_array(array_key_first($activity->changes), ['description', 'plan']))
            @php $change = collect($activity->changes)->first(); @endphp
            <span class="text-gray-400">· {{ str($change['old'] ?? 'Not set')->limit(35) }} →</span>
            <span class="text-gray-700 dark:text-gray-300">{{ str($change['new'] ?? 'Not set')->limit(35) }}</span>
        @endif
        <time class="ms-1 whitespace-nowrap" title="{{ $activity->created_at }}">· {{ $activity->created_at->format('M j, H:i') }}</time>
    </summary>
    <div class="mt-3 space-y-2 border-t border-gray-200 pt-3 dark:border-white/10">
        <p>{{ match ($activity->interface) { 'client_portal' => 'Client portal', 'panel' => 'Panel', default => 'System' } }} · {{ $activity->created_at->format('M j, Y H:i:s') }}</p>
        @if ($activity->subject)<p class="break-words text-gray-700 dark:text-gray-200">{{ $activity->subject }}</p>@endif
        @foreach ($activity->changes ?? [] as $field => $change)
            <p class="break-words"><span class="font-medium">{{ $field === 'department_id' ? 'Department' : str($field)->replace('_', ' ')->ucfirst() }}:</span>
                @if (! in_array($field, ['description', 'plan']))<span class="line-through">{{ $change['old'] ?? 'Not set' }}</span> →@endif
                <span class="text-gray-950 dark:text-white">{{ $change['new'] ?? 'Not set' }}</span>
            </p>
        @endforeach
    </div>
</details>

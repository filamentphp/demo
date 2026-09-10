<div class="min-h-screen bg-gray-50 text-gray-950 dark:bg-gray-950 dark:text-white">
    <main class="mx-auto max-w-6xl space-y-8 px-6 py-10">
        <header class="flex flex-wrap items-start justify-between gap-6">
            <div class="space-y-2">
                <p class="text-sm font-semibold text-blue-600 dark:text-blue-400">REALTIME DEMO · CLIENT APP</p>
                <h1 class="text-3xl font-semibold tracking-tight">Your projects</h1>
                <p class="max-w-xl text-sm text-gray-600 dark:text-gray-400">Make a change here and watch Filament update in another window. No refresh needed.</p>
            </div>
            <nav aria-label="Live Filament screens" class="flex flex-wrap gap-4 text-sm font-semibold text-blue-600 dark:text-blue-400">
                <a href="{{ \App\Filament\Resources\HR\Projects\ProjectResource::getUrl('index', panel: 'admin') }}" target="_blank" rel="noopener">Open live table ↗</a>
                <a href="{{ \App\Filament\Resources\HR\Projects\ProjectResource::getUrl('view', ['record' => $projectId], panel: 'admin') }}" target="_blank" rel="noopener">Open project ↗</a>
            </nav>
        </header>

        <div role="status" aria-live="polite" class="min-h-6 text-sm font-medium text-green-700 dark:text-green-400">{{ $message }}</div>

        <div class="grid items-start gap-6 lg:grid-cols-3">
            <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm lg:col-span-2 dark:border-gray-800 dark:bg-gray-900">
                <label for="project-id" class="mb-2 block text-sm font-medium">Select project</label>
                <select id="project-id" wire:replace.self wire:model.live="projectId" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900">
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" @selected($projectId === $project->id)>{{ $project->name }}</option>
                    @endforeach
                </select>

                <form wire:submit="save" wire:key="project-form-{{ $projectId }}" class="mt-6 space-y-5 border-t border-gray-200 pt-6 dark:border-gray-800">
                    <h2 class="text-lg font-semibold">Project details</h2>
                    <div>
                        <label for="project-name" class="mb-2 block text-sm font-medium">Project name</label>
                        <input id="project-name" wire:model="data.name" required maxlength="255" class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2.5 text-sm dark:border-gray-700" />
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="project-status" class="mb-2 block text-sm font-medium">Status</label>
                            <select id="project-status" wire:model="data.status" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900">
                                @foreach (\App\Enums\ProjectStatus::cases() as $status)
                                    <option value="{{ $status->value }}">{{ $status->getLabel() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="project-priority" class="mb-2 block text-sm font-medium">Priority</label>
                            <select id="project-priority" wire:model="data.priority" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900">
                                @foreach (\App\Enums\TaskPriority::cases() as $priority)
                                    <option value="{{ $priority->value }}">{{ $priority->getLabel() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="project-budget" class="mb-2 block text-sm font-medium">Budget (USD)</label>
                            <input id="project-budget" type="number" min="0" step="0.01" wire:model="data.budget" class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2.5 text-sm dark:border-gray-700" />
                        </div>
                        <div>
                            <label for="project-spent" class="mb-2 block text-sm font-medium">Spent (USD)</label>
                            <input id="project-spent" type="number" min="0" step="0.01" required wire:model="data.spent" class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2.5 text-sm dark:border-gray-700" />
                        </div>
                    </div>
                    @foreach (['data.name', 'data.status', 'data.priority', 'data.budget', 'data.spent'] as $field)
                        @error($field)<p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    @endforeach
                    <button type="submit" wire:loading.attr="disabled" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50">Save changes</button>
                </form>
            </section>

            <aside class="space-y-6">
                <form wire:submit="addTask" class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="space-y-2">
                        <h2 class="text-lg font-semibold">Add a task</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Watch the Tasks table on the project’s view page.</p>
                    </div>
                    <div>
                        <label for="task-title" class="mb-2 block text-sm font-medium">Task title</label>
                        <input id="task-title" wire:model="taskTitle" required maxlength="255" placeholder="Review the latest designs" class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2.5 text-sm dark:border-gray-700" />
                        @error('taskTitle')<p role="alert" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" wire:loading.attr="disabled" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50">Add task</button>
                </form>
                <div class="space-y-2 px-1 text-sm text-gray-600 dark:text-gray-400">
                    <h2 class="font-semibold text-gray-900 dark:text-gray-200">Read-only updates only</h2>
                    <p>Tables, headings, infolists and totals update live. Resource edit forms are left alone. Updates pause on a component while its action modal is open.</p>
                </div>
            </aside>
        </div>
    </main>
</div>

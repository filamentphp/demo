<div class="min-h-screen bg-gray-50 text-gray-950 dark:bg-gray-950 dark:text-white">
    <main class="mx-auto max-w-6xl space-y-8 px-6 py-10">
        <header class="flex flex-wrap items-start justify-between gap-6">
            <div class="space-y-2">
                <p class="text-sm font-semibold text-blue-600 dark:text-blue-400">CLIENT PORTAL</p>
                <h1 class="text-3xl font-semibold tracking-tight">Your projects</h1>
            </div>
            <nav aria-label="Filament screens" class="flex flex-wrap gap-4 text-sm font-semibold text-blue-600 dark:text-blue-400">
                <a href="{{ \App\Filament\Resources\HR\Projects\ProjectResource::getUrl('index', panel: 'admin') }}" target="_blank" rel="noopener">Open projects ↗</a>
                <a href="{{ \App\Filament\Resources\HR\Projects\ProjectResource::getUrl('view', ['record' => $projectId], panel: 'admin') }}" target="_blank" rel="noopener">Open project ↗</a>
            </nav>
        </header>

        <div role="status" aria-live="polite" class="min-h-6 text-sm font-medium text-green-700 dark:text-green-400">{{ $message }}</div>

        <div class="grid items-start gap-6 lg:grid-cols-3">
            <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm lg:col-span-2 dark:border-gray-800 dark:bg-gray-900">
                {{ $this->selectorForm }}

                <form wire:submit="save" wire:key="project-form-{{ $projectId }}" class="mt-6 space-y-5 border-t border-gray-200 pt-6 dark:border-gray-800">
                    <h2 class="text-lg font-semibold">Project details</h2>
                    {{ $this->projectForm }}
                    <x-filament::button type="submit" wire:loading.attr="disabled">Save changes</x-filament::button>
                </form>
            </section>

            <aside class="space-y-6">
                <form wire:submit="addTask" class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="space-y-2">
                        <h2 class="text-lg font-semibold">Add a task</h2>
                    </div>
                    {{ $this->taskForm }}
                    <x-filament::button type="submit" wire:loading.attr="disabled">Add task</x-filament::button>
                </form>
            </aside>
        </div>
    </main>
</div>

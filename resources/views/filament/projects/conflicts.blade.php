<x-filament::modal id="project-conflicts" width="3xl" style="z-index: 60" :close-by-clicking-away="false" sticky-header sticky-footer>
    <x-slot name="heading">Review conflicting changes</x-slot>
    <x-slot name="description">Someone saved changes to fields you also edited. Choose what to keep—nothing will be saved until you save the form.</x-slot>

    <div class="space-y-6">
        <div class="flex items-center gap-3 rounded-xl bg-amber-50 px-4 py-3 text-sm text-amber-900 ring-1 ring-amber-200/60 dark:bg-amber-400/10 dark:text-amber-200 dark:ring-amber-400/20">
            <x-filament::icon icon="heroicon-m-shield-check" class="size-5 shrink-0" />
            <p>Your draft is preserved. If a saved value changes again, we’ll ask you to review that field again.</p>
        </div>

        @foreach ($this->conflicts as $field => $conflict)
            <fieldset wire:key="conflict-{{ $field }}" class="space-y-3">
                <legend class="text-base font-semibold text-gray-950 dark:text-white">{{ $field === 'department_id' ? 'Department' : str($field)->replace('_', ' ')->ucfirst() }}</legend>
                <p class="text-xs text-gray-500 dark:text-gray-400">Originally: <span class="break-words">{{ $this->conflictValue($field, $conflict['original']) }}</span></p>
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach (['local' => 'Your draft', 'remote' => 'Latest saved'] as $side => $label)
                        <label @class([
                            'relative flex cursor-pointer gap-3 rounded-xl border p-4 transition hover:border-primary-400 focus-within:ring-2 focus-within:ring-primary-500',
                            'border-primary-500 bg-primary-50/70 dark:bg-primary-400/10' => ($this->conflictChoices[$field] ?? null) === $side,
                            'border-gray-200 bg-white dark:border-white/10 dark:bg-white/5' => ($this->conflictChoices[$field] ?? null) !== $side,
                        ])>
                            <input type="radio" wire:model.live="conflictChoices.{{ $field }}" value="{{ $side }}" name="conflict-{{ $field }}" class="mt-0.5 size-4 shrink-0 accent-primary-600" />
                            <span class="min-w-0 space-y-2">
                                <span class="block text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">{{ $label }}</span>
                                <span class="block max-h-48 overflow-auto text-sm font-medium whitespace-pre-wrap break-words text-gray-950 dark:text-white">{{ $this->conflictValue($field, $conflict[$side]) }}</span>
                                <span class="block text-xs leading-relaxed text-gray-500 dark:text-gray-400">{{ $side === 'local' ? 'Not saved yet' : $conflict['by'] }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('conflictChoices.' . $field)<p role="alert" class="text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>@enderror
            </fieldset>
        @endforeach
    </div>

    <x-slot name="footer">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ count($this->conflicts) }} {{ str('field')->plural(count($this->conflicts)) }} to review</p>
            <div class="flex gap-3">
                <x-filament::button color="gray" x-on:click="$dispatch('close-modal', { id: 'project-conflicts' })">Review later</x-filament::button>
                <x-filament::button wire:click="resolveConflicts" wire:loading.attr="disabled">Apply choices</x-filament::button>
            </div>
        </div>
    </x-slot>
</x-filament::modal>

<form wire:submit.prevent="submit" class="max-w-3xl mx-auto w-full p-8 space-y-6">
    {{ $this->form }}

    <x-filament::button>
        Submit
    </x-filament::button>
</form>

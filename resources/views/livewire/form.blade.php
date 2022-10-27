<form wire:submit.prevent="submit" class="max-w-xl mx-auto p-8">
    {{ $this->form }}

    <x-filament::button class="mt-2" type="submit">
        Submit
    </x-filament::button>

    {{ var_dump($data) }}
</form>

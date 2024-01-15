<form wire:submit="submit" class="max-w-3xl mx-auto w-full p-8 space-y-6">
    {{ $this->form }}

    {{ json_encode($this->data) }}

    <x-filament::button type="submit">
        Submit
    </x-filament::button>
</form>

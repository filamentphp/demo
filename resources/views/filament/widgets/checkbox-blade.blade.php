<x-filament-widgets::widget>
    <x-filament::section>
        <div class="checkbox-demo" data-framework="blade">
            <h2 class="fi-section-header-heading">Blade · reference</h2>
            <label><x-filament::input.checkbox checked /> Send me the weekly digest</label>
            <label><x-filament::input.checkbox :valid="false" /> Invalid</label>
            <label><x-filament::input.checkbox checked disabled /> Disabled</label>
            <p>The framework examples submit only to a local preview; no data is sent.</p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

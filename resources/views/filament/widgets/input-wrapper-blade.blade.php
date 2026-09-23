<x-filament-widgets::widget>
    <x-filament::section>
        <div class="icon-demo" data-framework="blade">
            <h2 class="fi-section-header-heading">Blade · reference</h2>
            <label for="blade-price">Workshop price</label>
            <x-filament::input.wrapper prefix="£" suffix="GBP">
                <input
                    id="blade-price"
                    class="fi-input"
                    type="number"
                    min="1"
                    value="125"
                    required
                />
            </x-filament::input.wrapper>
            <label for="blade-price-invalid">Price needing attention</label>
            <x-filament::input.wrapper
                :valid="false"
                prefix="£"
                suffix="GBP"
                inline-prefix
                inline-suffix
            >
                <input
                    id="blade-price-invalid"
                    class="fi-input"
                    type="number"
                    min="1"
                    value="0"
                    required
                    aria-invalid="true"
                    aria-describedby="blade-price-error"
                />
            </x-filament::input.wrapper>
            <p id="blade-price-error">Enter a workshop price of at least £1.</p>
            <label for="blade-price-disabled">Archived workshop</label>
            <x-filament::input.wrapper
                disabled
                prefix-icon="heroicon-m-currency-pound"
                suffix="GBP"
            >
                <input
                    id="blade-price-disabled"
                    class="fi-input"
                    type="number"
                    value="125"
                    disabled
                />
            </x-filament::input.wrapper>
            <p>
                The wrappers share Filament's markup and theme. Each contains a
                native input, not a JavaScript Input adapter.
            </p>
            <p>
                Try removing text, adding an icon or Clear action, validating an
                empty price, and resetting. PHP actions, icon aliases and
                Livewire loading remain host-owned.
            </p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

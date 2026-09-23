<x-filament-widgets::widget>
    <x-filament::section>
        <div
            class="radio-demo"
            data-framework="blade"
            x-data="{ result: 'No submission yet', disabled: false }"
        >
            <h2 class="fi-section-header-heading">Blade · reference</h2>
            <form
                x-ref="form"
                x-on:submit.prevent="result = JSON.stringify([...new FormData($event.currentTarget)])"
                x-on:reset="result = 'Reset to defaults'"
            >
                <label for="blade-workshop">Main workshop</label>
                <x-filament::input.wrapper prefix="Art">
                    <x-filament::input.select
                        id="blade-workshop"
                        data-testid="workshop"
                        name="workshop"
                        required
                        x-bind:disabled="disabled"
                    >
                        <option value="">Choose a workshop</option>
                        <option value="drawing" selected>
                            Botanical drawing
                        </option>
                        <option value="ceramics">Studio ceramics</option>
                        <option value="printing">Printmaking</option>
                        <option value="archived" disabled>
                            Watercolours (unavailable)
                        </option>
                    </x-filament::input.select>
                </x-filament::input.wrapper>
                <label for="blade-extras">
                    Additional workshops (multiple)
                </label>
                <x-filament::input.wrapper>
                    <x-filament::input.select
                        id="blade-extras"
                        data-testid="extras"
                        name="extras"
                        multiple
                        size="4"
                        x-bind:disabled="disabled"
                    >
                        <option value="drawing">Botanical drawing</option>
                        <option value="ceramics" selected>
                            Studio ceramics
                        </option>
                        <option value="printing">Printmaking</option>
                        <option value="archived" disabled>
                            Watercolours (unavailable)
                        </option>
                    </x-filament::input.select>
                </x-filament::input.wrapper>
                <label for="blade-delivery">Ticket delivery</label>
                <x-filament::input.wrapper>
                    <x-filament::input.select
                        id="blade-delivery"
                        name="delivery"
                        data-testid="delivery"
                    >
                        <option value="email" selected>Email tickets</option>
                        <option value="desk">Collect at reception</option>
                    </x-filament::input.select>
                </x-filament::input.wrapper>
                <div class="radio-demo-controls">
                    <button type="submit" data-testid="submit">
                        Submit locally
                    </button>
                    <button type="reset" data-testid="reset">Reset form</button>
                </div>
            </form>
            <div class="radio-demo-controls">
                <label>
                    <input
                        type="checkbox"
                        data-testid="disabled"
                        x-model="disabled"
                    />
                    Disable workshops
                </label>
                <button
                    type="button"
                    data-testid="empty"
                    x-on:click="
                        $refs.form.elements.workshop.value = ''
                        ;[...$refs.form.elements.extras.options].forEach(
                            (option) => (option.selected = false),
                        )
                    "
                >
                    Clear selections
                </button>
                <button
                    type="button"
                    data-testid="validate"
                    x-on:click="result = $refs.form.reportValidity() ? 'Valid' : 'Invalid'"
                >
                    Validate
                </button>
            </div>
            <output data-testid="result" x-text="result"></output>
            <p>
                Native Support select with browser defaults. Hold Ctrl or
                Command to select several workshops. No database writes; all
                submissions stay in this browser.
            </p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

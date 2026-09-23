<x-filament-widgets::widget>
    <x-filament::section>
        <div
            class="radio-demo"
            data-framework="blade"
            x-data="{ result: 'No submission yet' }"
        >
            <h2 class="fi-section-header-heading">Blade · reference</h2>
            <form
                x-on:submit.prevent="result = JSON.stringify(Object.fromEntries(new FormData($event.currentTarget)))"
                x-on:reset="result = 'Reset to defaults'"
            >
                <label for="blade-title">Workshop title</label>
                <x-filament::input.wrapper>
                    <x-filament::input
                        id="blade-title"
                        name="title"
                        value="Botanical drawing"
                        required
                    />
                </x-filament::input.wrapper>
                <label for="blade-email">Contact email</label>
                <x-filament::input.wrapper>
                    <x-filament::input
                        id="blade-email"
                        name="email"
                        type="email"
                        value="ada@example.com"
                        required
                    />
                </x-filament::input.wrapper>
                <label for="blade-price">Ticket price</label>
                <x-filament::input.wrapper prefix="£" suffix="GBP">
                    <x-filament::input
                        id="blade-price"
                        name="price"
                        type="number"
                        min="0"
                        step="0.5"
                        value="0"
                        required
                    />
                </x-filament::input.wrapper>
                <div class="radio-demo-controls">
                    <button type="submit">Submit locally</button>
                    <button type="reset">Reset form</button>
                </div>
            </form>
            <output data-testid="result" x-text="result"></output>
            <p>
                Native text, email, and number inputs. All submissions stay in
                this browser; nothing is saved to the database.
            </p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

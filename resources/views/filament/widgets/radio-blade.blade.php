<x-filament-widgets::widget>
    <x-filament::section>
        <div
            class="radio-demo"
            data-framework="blade"
            x-data="{
                disabled: false,
                invalid: false,
                required: false,
                submitted: 'Not previewed',
            }"
        >
            <h2 class="fi-section-header-heading">Blade · reference</h2>
            <form
                x-ref="form"
                x-on:submit.prevent="submitted = JSON.stringify([...new FormData($event.target)])"
                x-on:reset="submitted = 'Not previewed'"
            >
                <fieldset>
                    <legend>Delivery method</legend>
                    <label>
                        <x-filament::input.radio
                            name="delivery"
                            value="standard"
                            checked
                            x-bind:disabled="disabled"
                            x-bind:required="required"
                            x-bind:aria-invalid="invalid"
                            x-bind:class="{ 'fi-invalid': invalid }"
                        />
                        Standard · 3–5 working days
                    </label>
                    <label>
                        <x-filament::input.radio
                            name="delivery"
                            value="express"
                            x-bind:disabled="disabled"
                            x-bind:required="required"
                            x-bind:aria-invalid="invalid"
                            x-bind:class="{ 'fi-invalid': invalid }"
                        />
                        Express · next working day
                    </label>
                </fieldset>
                <div class="radio-demo-controls">
                    <x-filament::button type="submit" size="sm">
                        Preview FormData
                    </x-filament::button>
                    <x-filament::button type="reset" size="sm">
                        Reset delivery
                    </x-filament::button>
                    <x-filament::button
                        size="sm"
                        x-on:click="$refs.form.querySelectorAll('input[type=radio]').forEach(input => input.checked = false)"
                    >
                        Clear selection
                    </x-filament::button>
                </div>
            </form>
            <div class="radio-demo-controls">
                <label>
                    <x-filament::input.checkbox x-model="disabled" />
                    Disabled
                </label>
                <label>
                    <x-filament::input.checkbox x-model="invalid" />
                    Invalid
                </label>
                <label>
                    <x-filament::input.checkbox x-model="required" />
                    Required
                </label>
            </div>
            <output x-text="submitted"></output>
            <p>Preview stays in this browser. No order is placed.</p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

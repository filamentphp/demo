<x-filament-widgets::widget>
    <x-filament::section>
        <div
            class="callout-demo"
            data-framework="blade"
            x-data="{ visible: true, message: 'No changes published.' }"
        >
            <h2 class="fi-section-header-heading">Blade · reference</h2>
            <x-filament::callout
                heading="Your next release"
                data-testid="callout"
                x-show="visible"
            >
                <x-slot name="description">
                    Review the
                    <strong>release notes</strong>
                    before publishing.
                </x-slot>
                <x-slot name="footer">
                    <button
                        type="button"
                        data-testid="review"
                        x-on:click="message = 'Release notes reviewed locally.'"
                    >
                        Review notes
                    </button>
                    <a href="#blade-release">View release</a>
                </x-slot>
                <x-slot name="controls">
                    <button
                        type="button"
                        data-testid="dismiss"
                        aria-label="Dismiss release notice"
                        x-on:click="visible = false; message = 'Release notice dismissed locally.'"
                    >
                        ×
                    </button>
                </x-slot>
            </x-filament::callout>
            <x-filament::callout
                color="brand"
                icon="heroicon-o-information-circle"
                heading="A registered brand color"
                description="The background, ring, and icon use the host's custom palette."
            />
            <p>
                Use the JavaScript controls to explore colors, icons, and
                content.
            </p>
            <button
                type="button"
                data-testid="reset"
                x-on:click="visible = true; message = 'No changes published.'"
            >
                Reset
            </button>
            <p role="status" data-testid="result" x-text="message"></p>
            <p id="blade-release" tabindex="-1">
                September release · Ready for review
            </p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

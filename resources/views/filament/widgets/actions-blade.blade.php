<x-filament-widgets::widget>
    <x-filament::section>
        <div
            class="actions-demo"
            data-framework="blade"
            x-data="{ name: 'Community garden', message: 'No changes saved.' }"
        >
            <h2 class="fi-section-header-heading">Blade · reference</h2>
            <form x-on:submit.prevent="message = `Saved ${name} locally.`">
                <label>
                    Project name
                    <input x-model="name" data-testid="name" />
                </label>
                <x-filament::actions data-testid="actions">
                    <button type="submit" data-testid="save">Save</button>
                    <button
                        type="button"
                        data-testid="archive"
                        x-on:click="message = `Archived ${name} locally.`"
                    >
                        Archive
                    </button>
                    <a href="#blade-project">View project</a>
                </x-filament::actions>
            </form>
            <p>
                Use the JavaScript controls to explore alignment, full width,
                and host-owned visibility.
            </p>
            <div class="icon-demo-controls">
                <button
                    type="button"
                    data-testid="reset"
                    x-on:click="name = 'Community garden'; message = 'No changes saved.'"
                >
                    Reset
                </button>
            </div>
            <p role="status" data-testid="result" x-text="message"></p>
            <p id="blade-project" tabindex="-1">
                Community garden · Project details
            </p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

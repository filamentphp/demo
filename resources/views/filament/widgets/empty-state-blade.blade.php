<x-filament-widgets::widget>
    <x-filament::section>
        <div
            class="radio-demo"
            data-framework="blade"
            x-data="{ created: 0 }"
        >
            <h2 class="fi-section-header-heading">Blade · reference</h2>
            <x-filament::empty-state
                heading="No projects yet"
                heading-tag="h3"
                icon="heroicon-o-folder-plus"
                data-testid="empty-state"
                title="All projects"
            >
                <x-slot name="description">
                    Make room for your next
                    <strong>great idea.</strong>
                </x-slot>
                <x-slot name="footer">
                    <div class="radio-demo-controls">
                        <button
                            type="button"
                            data-testid="create"
                            x-on:click="created++"
                        >
                            Create project
                        </button>
                        <a href="#blade-projects">View workspace</a>
                    </div>
                </x-slot>
            </x-filament::empty-state>
            <p>
                Use the JavaScript examples to switch layouts and remove
                content.
            </p>
            <button
                type="button"
                data-testid="reset"
                x-on:click="created = 0"
            >
                Reset
            </button>
            <p role="status" data-testid="result">
                Drafts:
                <span x-text="created"></span>
            </p>
            <div id="blade-projects" tabindex="-1">
                Workspace ·
                <span
                    x-text="created ? created + ' project drafts' : 'No saved projects'"
                ></span>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

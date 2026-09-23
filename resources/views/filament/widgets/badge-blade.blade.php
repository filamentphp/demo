<x-filament-widgets::widget>
    <x-filament::section>
        <div
            class="callout-demo"
            data-framework="blade"
            x-data="{ count: 0, removed: false }"
        >
            <h2 class="fi-section-header-heading">Blade · Project labels</h2>
            <p>
                Blade reference. Local-only interactions; Alt+B saves filters.
            </p>
            <div class="radio-demo-controls" data-testid="palette">
                <x-filament::badge>New</x-filament::badge>
                <x-filament::badge color="gray" size="xs">
                    Archived
                </x-filament::badge>
                <x-filament::badge
                    color="brand"
                    size="sm"
                    icon="heroicon-m-star"
                >
                    Priority
                </x-filament::badge>
                <x-filament::badge
                    color="success"
                    icon="heroicon-m-star"
                    icon-position="after"
                >
                    Approved
                </x-filament::badge>
            </div>
            <div class="radio-demo-controls">
                <x-filament::badge
                    tag="a"
                    href="#blade-projects"
                    tooltip="Jump to the project summary"
                >
                    View projects
                </x-filament::badge>
                <x-filament::badge
                    tag="button"
                    type="submit"
                    form-id="blade-badge-form"
                    color="brand"
                    icon="heroicon-m-star"
                    tooltip="Save project filters (Alt+B)"
                    :key-bindings="['alt+b']"
                >
                    Save filters
                </x-filament::badge>
                <template x-if="!removed">
                    <x-filament::badge color="warning">
                        Priority filter
                        <x-slot
                            name="deleteButton"
                            label="Remove priority filter"
                            x-on:click="removed = true"
                        ></x-slot>
                    </x-filament::badge>
                </template>
                <x-filament::badge
                    tag="button"
                    disabled
                    tooltip="You need permission to approve"
                >
                    Approval locked
                </x-filament::badge>
            </div>
            <form id="blade-badge-form" x-on:submit.prevent="count++">
                <label>
                    Filter name
                    <input value="Community garden" />
                </label>
            </form>
            <div class="icon-demo-controls">
                <button
                    type="button"
                    x-on:click="count = 0; removed = false"
                >
                    Reset
                </button>
            </div>
            <p id="blade-projects">Community garden · 12 projects</p>
            <p
                role="status"
                x-text="`${count} saves requested. ${removed ? 'Priority filter removed.' : 'Ready.'}`"
            ></p>
            <p>
                JavaScript cards add explicit, host-controlled loading and
                mount/unmount checks.
            </p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

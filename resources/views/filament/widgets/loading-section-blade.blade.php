<x-filament-widgets::widget>
    <x-filament::section>
        <div
            class="actions-demo"
            data-framework="blade"
            x-data="{
                state: 'loading',
                tall: false,
                visible: true,
                gridOffset: false,
            }"
        >
            <h2 class="fi-section-header-heading">Blade · Project overview</h2>
            <p>
                Load the latest community garden milestones. Finish manually to
                explore success and failure.
            </p>
            <div
                class="fi-grid"
                data-testid="preview-grid"
                style="--cols-default: repeat(4, minmax(0, 1fr))"
            >
                <template
                    x-if="visible && state === 'loading' && !gridOffset"
                >
                    <x-filament::loading-section
                        data-testid="loading-section"
                        loading-label="Loading project overview"
                        :column-span="['default' => 'full']"
                        x-bind:style="{ height: tall ? '12rem' : '8rem' }"
                    />
                </template>
                <template
                    x-if="visible && state === 'loading' && gridOffset"
                >
                    <x-filament::loading-section
                        data-testid="loading-section"
                        loading-label="Loading project overview"
                        :column-span="['default' => 'full', 'lg' => 2]"
                        :column-start="['lg' => 3]"
                        x-bind:style="{ height: tall ? '12rem' : '8rem' }"
                    />
                </template>
                <template x-if="visible && state !== 'loading'">
                    <div
                        class="fi-section fi-grid-col"
                        style="--col-span-default: 1 / -1"
                        data-testid="content"
                    >
                        <div
                            class="fi-section-content"
                            x-text="
                                state === 'ready'
                                    ? 'Community garden · 8 of 12 milestones complete.'
                                    : 'Project overview could not be loaded. Try again.'
                            "
                        ></div>
                    </div>
                </template>
            </div>
            <div class="icon-demo-controls">
                <button
                    type="button"
                    data-testid="load"
                    x-bind:disabled="state === 'loading'"
                    x-on:click="state = 'loading'"
                >
                    Load overview
                </button>
                <button
                    type="button"
                    data-testid="finish"
                    x-bind:disabled="state !== 'loading'"
                    x-on:click="state = 'ready'"
                >
                    Finish successfully
                </button>
                <button
                    type="button"
                    data-testid="fail"
                    x-bind:disabled="state !== 'loading'"
                    x-on:click="state = 'error'"
                >
                    Simulate error
                </button>
                <button
                    type="button"
                    data-testid="reset"
                    x-on:click="state = 'loading'; tall = false; visible = true; gridOffset = false"
                >
                    Reset
                </button>
            </div>
            <label>
                <input type="checkbox" data-testid="tall" x-model="tall" />
                Taller placeholder
            </label>
            <label>
                <input
                    type="checkbox"
                    data-testid="visible"
                    x-model="visible"
                />
                Show preview
            </label>
            <p
                role="status"
                data-testid="result"
                x-text="
                    state === 'ready'
                        ? 'Project overview is ready.'
                        : state === 'error'
                          ? 'Loading failed. You can retry.'
                          : ''
                "
            ></p>
            <label>
                <input
                    type="checkbox"
                    data-testid="grid-offset"
                    x-model="gridOffset"
                />
                Use columns 3–4 on wide screens (1024px+)
            </label>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

@php
    $selection = \App\LiveDemo\Selection::current();
@endphp

@vite(['resources/css/live-demo-toolbar.css', 'resources/js/live-demo.js'])

<aside class="live-demo-toolbar" data-live-demo-toolbar aria-label="Theme preview">
    <button
        class="live-demo-launcher"
        type="button"
        popovertarget="live-demo-toolbar-controls"
        popovertargetaction="show"
        data-live-demo-toolbar-toggle
        aria-label="Explore themes"
    >
        <x-filament::icon icon="heroicon-o-swatch" class="live-demo-launcher__icon" />
        <span class="live-demo-launcher__label" aria-hidden="true">Explore themes</span>
    </button>

    <section
        id="live-demo-toolbar-controls"
        class="live-demo-studio"
        popover="auto"
        aria-labelledby="live-demo-studio-title"
        data-live-demo-toolbar-controls
        data-live-demo-open="{{ $selection['expanded'] ? 'true' : 'false' }}"
    >
        <header class="live-demo-studio__header">
            <span class="live-demo-studio__badge" aria-hidden="true">
                <x-filament::icon icon="heroicon-o-swatch" />
            </span>
            <div>
                <h2 id="live-demo-studio-title">Make it yours</h2>
                <p>Same Filament. A different feel.</p>
            </div>
            <button
                type="button"
                class="live-demo-studio__close fi-icon-btn"
                popovertarget="live-demo-toolbar-controls"
                popovertargetaction="hide"
                data-live-demo-close
                aria-label="Close theme preview"
            >
                <x-filament::icon icon="heroicon-m-x-mark" />
            </button>
        </header>

        <div class="live-demo-studio__body">
            <fieldset class="live-demo-studio__themes">
                <legend>Theme</legend>
                <div class="live-demo-studio__theme-grid">
                    @foreach (['stock' => ['Stock', 'The original', 'heroicon-o-squares-2x2'], 'sharp' => ['Sharp', 'Bold & precise', 'heroicon-o-square-3-stack-3d'], 'soft' => ['Soft', 'Warm & welcoming', 'heroicon-o-sparkles'], 'noir' => ['Noir', 'Quietly confident', 'heroicon-o-moon']] as $value => [$label, $description, $icon])
                        <label class="live-demo-theme live-demo-theme--{{ $value }}">
                            <input
                                class="fi-radio-input"
                                type="radio"
                                name="live-demo-theme"
                                value="{{ $value }}"
                                data-live-demo-theme
                                @checked($selection['theme'] === $value)
                            />
                            <span class="live-demo-theme__icon" aria-hidden="true">
                                <x-filament::icon :icon="$icon" />
                            </span>
                            <span class="live-demo-theme__name">{{ $label }}</span>
                            <span class="live-demo-theme__description">{{ $description }}</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>

            <label class="live-demo-density">
                <span>
                    <span class="live-demo-studio__label">Compact layout</span>
                    <span class="live-demo-studio__hint">A little less space. More in view.</span>
                </span>
                <input type="checkbox" role="switch" data-live-demo-compact @checked($selection['compact']) />
                <span class="live-demo-density__switch" aria-hidden="true"></span>
            </label>

            <fieldset class="live-demo-appearance">
                <legend>Appearance</legend>
                <div class="live-demo-appearance__options" role="group" aria-label="Color scheme">
                    <button type="button" data-live-demo-scheme="light" aria-pressed="false">
                        <x-filament::icon icon="heroicon-o-sun" />
                        Light
                    </button>
                    <button type="button" data-live-demo-scheme="dark" aria-pressed="false">
                        <x-filament::icon icon="heroicon-o-moon" />
                        Dark
                    </button>
                </div>
            </fieldset>
        </div>

        <footer class="live-demo-studio__footer">
            <span class="live-demo-studio__status" aria-hidden="true"></span>
            Previewing live on this demo
        </footer>
    </section>
</aside>

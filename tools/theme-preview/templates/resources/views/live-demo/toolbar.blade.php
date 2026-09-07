@php
    $selection = \App\LiveDemo\Selection::current();
@endphp

@vite(['resources/css/live-demo-toolbar.css', 'resources/js/live-demo.js'])
<link rel="stylesheet" href="https://fonts.bunny.net/css?family=albert-sans:400,500,600,700|lora:500,600&display=swap" />

<aside class="live-demo-toolbar" data-live-demo-toolbar data-preview-theme="{{ $selection['theme'] }}" data-preview-compact="{{ $selection['compact'] ? 'true' : 'false' }}" aria-label="Theme preview">
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
            <div>
                <h2 id="live-demo-studio-title">Find your Filament.</h2>
                <p>Official themes. Try them right here.</p>
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
            <fieldset class="live-demo-appearance">
                <legend>Color scheme</legend>
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

            <fieldset class="live-demo-studio__themes">
                <legend>Choose a look</legend>
                <div class="live-demo-studio__theme-grid">
                    @foreach (['stock' => ['Default', 'Familiar Filament', 'Free'], 'sharp' => ['Sharp', 'Precise & technical', '$39'], 'soft' => ['Soft', 'Warm & welcoming', '$39'], 'noir' => ['Noir', 'Dark-first depth', '$39']] as $value => [$label, $description, $price])
                        <label class="live-demo-theme live-demo-theme--{{ $value }}">
                            <input
                                class="fi-radio-input"
                                type="radio"
                                name="live-demo-theme"
                                value="{{ $value }}"
                                data-live-demo-theme
                                @checked($selection['theme'] === $value)
                            />
                            <span class="live-demo-theme__name">{{ $label }}</span>
                            <span class="live-demo-theme__description">{{ $description }}</span>
                            <span class="live-demo-theme__price">{{ $price }}</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>

            <label class="live-demo-density">
                <span>
                    <span class="live-demo-studio__label">Add Compact <span class="live-demo-density__price">$29</span></span>
                    <span class="live-demo-studio__hint">More room on desktop. Same spacing on mobile.</span>
                </span>
                <input type="checkbox" role="switch" data-live-demo-compact @checked($selection['compact']) />
                <span class="live-demo-density__switch" aria-hidden="true"></span>
            </label>

            <div class="live-demo-shop">
                <a href="https://filamentphp.com/themes" target="_blank" rel="noopener" class="live-demo-shop__link" aria-label="Explore themes & pricing (opens in a new tab)">
                    Explore themes & pricing
                    <x-filament::icon icon="heroicon-m-arrow-up-right" />
                </a>
                <p>USD · Single-project licenses</p>
            </div>
        </div>
    </section>
</aside>

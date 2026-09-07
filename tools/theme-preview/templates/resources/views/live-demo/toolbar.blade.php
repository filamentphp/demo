@php
    $selection = \App\LiveDemo\Selection::current();
    session()->forget('live-demo.open');
@endphp

@vite(['resources/css/live-demo-toolbar.css', 'resources/js/live-demo.js'])
<link rel="stylesheet" href="https://fonts.bunny.net/css?family=albert-sans:400,500,600,700|lora:500,600&display=swap" />

<aside class="live-demo-toolbar" data-live-demo-toolbar data-preview-theme="{{ $selection['theme'] }}" data-preview-compact="{{ $selection['compact'] ? 'true' : 'false' }}" aria-label="Theme preview">
    <button
        class="live-demo-launcher"
        type="button"
        popovertarget="live-demo-toolbar-controls"
        popovertargetaction="toggle"
        data-live-demo-toolbar-toggle
        aria-label="Switch theme"
    >
        <span class="live-demo-launcher__label" aria-hidden="true">Switch theme</span>
        <x-filament::icon :alias="\App\Filament\DemoIconAlias::THEME_PREVIEW_TRIGGER" icon="heroicon-o-swatch" class="live-demo-launcher__icon" />
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
                <h2 id="live-demo-studio-title">Filament themes</h2>
                <p>Choose a new look, a denser layout, or both.</p>
            </div>
            <x-filament::icon-button
                color="gray"
                icon="heroicon-m-x-mark"
                :icon-alias="\App\Filament\DemoIconAlias::THEME_PREVIEW_CLOSE"
                class="live-demo-studio__close"
                popovertarget="live-demo-toolbar-controls"
                popovertargetaction="hide"
                data-live-demo-close
                label="Close theme preview"
            />
        </header>

        <div class="live-demo-studio__body">
            <fieldset class="live-demo-appearance">
                <legend>Color scheme</legend>
                <x-filament::tabs class="grid w-full grid-cols-2" label="Color scheme" role="group">
                    <x-filament::tabs.item data-live-demo-scheme="light" aria-pressed="false" :icon="\Filament\Support\Facades\FilamentIcon::resolve(\App\Filament\DemoIconAlias::THEME_PREVIEW_LIGHT) ?? 'heroicon-o-sun'">
                        Light
                    </x-filament::tabs.item>
                    <x-filament::tabs.item data-live-demo-scheme="dark" aria-pressed="false" :icon="\Filament\Support\Facades\FilamentIcon::resolve(\App\Filament\DemoIconAlias::THEME_PREVIEW_DARK) ?? 'heroicon-o-moon'">
                        Dark
                    </x-filament::tabs.item>
                </x-filament::tabs>
            </fieldset>

            <fieldset class="live-demo-studio__themes">
                <legend>Choose a look</legend>
                <div class="live-demo-studio__theme-grid">
                    @foreach (['stock' => ['Default', 'Familiar Filament', 'Free'], 'sharp' => ['Sharp', 'Precise & technical', '$39 USD'], 'soft' => ['Soft', 'Warm & welcoming', '$39 USD'], 'noir' => ['Noir', 'Dark-first depth', '$39 USD']] as $value => [$label, $description, $price])
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

            <div class="live-demo-density">
                <span>
                    <label for="live-demo-compact" class="live-demo-studio__label">Add Compact <span class="live-demo-density__price">$29 USD</span></label>
                    <span id="live-demo-compact-hint" class="live-demo-studio__hint">More room on desktop. Same spacing on mobile.</span>
                </span>
                <x-filament::toggle id="live-demo-compact" :state="$selection['compact'] ? 'true' : 'false'" data-live-demo-compact aria-describedby="live-demo-compact-hint" />
            </div>

            <div class="live-demo-shop">
                <a href="https://filamentphp.com/themes" target="_blank" rel="noopener" class="live-demo-shop__link" aria-label="Explore themes & pricing (opens in a new tab)">
                    Explore themes & pricing
                    <x-filament::icon :alias="\App\Filament\DemoIconAlias::THEME_PREVIEW_PRICING" icon="heroicon-m-arrow-up-right" />
                </a>
            </div>
        </div>
        <div class="live-demo-loading" data-live-demo-loading hidden>
            <div role="status">
                <span class="live-demo-loading__spinner" aria-hidden="true"></span>
                <p>Refreshing the demo…</p>
            </div>
        </div>
    </section>
</aside>

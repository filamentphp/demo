@php
    $selection = \App\LiveDemo\Selection::current();
@endphp

@vite(['resources/css/live-demo-toolbar.css', 'resources/js/live-demo.js'])

<aside
    class="live-demo-toolbar"
    data-live-demo-toolbar
    aria-label="Theme preview controls"
>
    <button
        class="live-demo-toolbar__explore"
        type="button"
        data-live-demo-toolbar-toggle
        aria-controls="live-demo-toolbar-controls"
        aria-expanded="{{ $selection['expanded'] ? 'true' : 'false' }}"
    >
        <span>Explore themes</span>
        <span class="live-demo-toolbar__chevron" aria-hidden="true"></span>
    </button>

    <div
        id="live-demo-toolbar-controls"
        class="live-demo-toolbar__controls"
        data-live-demo-toolbar-controls
        @if (! $selection['expanded']) hidden @endif
    >
        <label class="live-demo-toolbar__field">
            <span class="live-demo-toolbar__label">Theme</span>
            <select class="live-demo-toolbar__select" data-live-demo-theme>
                @foreach (['stock' => 'Stock', 'sharp' => 'Sharp', 'soft' => 'Soft', 'noir' => 'Noir'] as $value => $label)
                    <option value="{{ $value }}" @selected($selection['theme'] === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </label>

        <label class="live-demo-toolbar__check">
            <input
                class="live-demo-toolbar__checkbox"
                type="checkbox"
                data-live-demo-compact
                @checked($selection['compact'])
            />
            <span>Compact</span>
        </label>

        <button
            class="live-demo-toolbar__scheme"
            type="button"
            data-live-demo-scheme
            aria-label="Use dark color scheme"
            aria-pressed="false"
        >
            <span class="live-demo-toolbar__sun" aria-hidden="true">☀</span>
            <span class="live-demo-toolbar__moon" aria-hidden="true">☾</span>
            <span data-live-demo-scheme-label>Light</span>
        </button>
    </div>
</aside>

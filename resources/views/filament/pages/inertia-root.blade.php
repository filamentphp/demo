@php
    use Inertia\Ssr\SsrState;

    $hasServerRenderedContent = (bool) app(SsrState::class)->setPage($page)->dispatch();
@endphp

<div data-inertia-stage aria-busy="true">
    <div
        class="fi-inertia-loading"
        data-inertia-loading
        role="status"
        @if ($hasServerRenderedContent) hidden @endif
    >
        <x-filament::loading-indicator />
        <span>Loading page…</span>
    </div>

    <div class="fi-inertia-error" data-inertia-error hidden>
        <p role="alert">
            The page could not be loaded. Reload the page to try again.
        </p>
        <x-filament::button type="button" color="gray" data-inertia-retry>
            Reload page
        </x-filament::button>
    </div>

    @inertia('filament-inertia')
</div>

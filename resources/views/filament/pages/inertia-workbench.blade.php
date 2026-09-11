@php
    use Illuminate\Foundation\Vite;

    $vite = app(Vite::class)->createAssetPathsUsing(static fn (string $path): string => "/{$path}");
@endphp

<x-filament-panels::page>
    @push('styles')
        {{ $vite('resources/css/inertia-workbench.css') }}
    @endpush

    <script data-navigate-once>
        ;(() => {
            const register = () =>
                Alpine.data('inertiaHost', (moduleUrl, spa) => {
                    let dispose
                    let stopped = false
                    let originalMarkup
                    let container

                    const stop = () => {
                        if (stopped) return
                        stopped = true
                        dispose?.()
                        // Cache the original SSR tree, not a hydrated tree with stale initial props.
                        if (container) container.innerHTML = originalMarkup
                    }

                    return {
                        init() {
                            container = this.$el.querySelector(
                                '[data-inertia-container]',
                            )
                            originalMarkup = container.innerHTML
                            const root =
                                container.querySelector('#filament-inertia')
                            const stage = container.querySelector(
                                '[data-inertia-stage]',
                            )
                            const loading = container.querySelector(
                                '[data-inertia-loading]',
                            )
                            const error = container.querySelector(
                                '[data-inertia-error]',
                            )
                            container.querySelector(
                                '[data-inertia-retry]',
                            ).onclick = () => location.reload()
                            const pageKey = location.pathname + location.search
                            document.addEventListener(
                                'livewire:navigating',
                                stop,
                            )

                            requestAnimationFrame(() =>
                                setTimeout(async () => {
                                    if (stopped) return
                                    try {
                                        const { default: mount } = await import(
                                            moduleUrl
                                        )
                                        if (stopped) return
                                        dispose = await mount(
                                            root,
                                            {
                                                navigate(url) {
                                                    if (
                                                        spa &&
                                                        new URL(
                                                            url,
                                                            location.href,
                                                        ).origin ===
                                                            location.origin
                                                    ) {
                                                        Livewire.navigate(url)
                                                    } else {
                                                        location.assign(url)
                                                    }
                                                },
                                                remember(data, key) {
                                                    sessionStorage.setItem(
                                                        `filament-inertia:${pageKey}:${key}`,
                                                        JSON.stringify(data),
                                                    )
                                                },
                                                restore(key) {
                                                    const data =
                                                        sessionStorage.getItem(
                                                            `filament-inertia:${pageKey}:${key}`,
                                                        )
                                                    return data === null
                                                        ? undefined
                                                        : JSON.parse(data)
                                                },
                                            },
                                            () => {
                                                if (stopped) return
                                                loading.hidden = true
                                                stage.setAttribute(
                                                    'aria-busy',
                                                    'false',
                                                )
                                            },
                                        )
                                        if (stopped) dispose?.()
                                    } catch (exception) {
                                        if (stopped) return
                                        loading.hidden = true
                                        stage.setAttribute('aria-busy', 'false')
                                        error.hidden = false
                                        console.error(
                                            'Unable to mount the Inertia page.',
                                            exception,
                                        )
                                    }
                                }, 0),
                            )
                        },
                        destroy() {
                            document.removeEventListener(
                                'livewire:navigating',
                                stop,
                            )
                            stop()
                        },
                    }
                })

            if (window.Alpine) register()
            else
                document.addEventListener('alpine:init', register, {
                    once: true,
                })
        })()
    </script>

    <x-filament::section heading="Livewire owns this page">
        <p>
            The sidebar and this control are Livewire. The report below is an
            Inertia {{ $framework }} application.
        </p>
        <x-filament::button
            color="gray"
            wire:click="refreshShell"
            id="refresh-shell"
        >
            Refresh Livewire shell
        </x-filament::button>
        <p role="status">
            Livewire refreshes:
            <span id="shell-refresh-count">{{ $refreshCount }}</span>
        </p>
        <p>
            Livewire mounted section:
            <strong id="mounted-section">{{ $mountedSection }}</strong>
        </p>
    </x-filament::section>

    <div
        x-data="inertiaHost(@js($vite->asset($rendererModule)), @js(filament()->getCurrentPanel()->hasSpaMode()))"
        wire:key="inertia-host"
    >
        <div wire:ignore x-ignore data-inertia-container>
            {{ $inertiaView }}
        </div>
    </div>
</x-filament-panels::page>

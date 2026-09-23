@php
    use Filament\Support\Enums\IconSize;
    use Illuminate\Support\HtmlString;

    $check = new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"></path></svg>');
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="icon-demo" data-framework="blade">
            <h2 class="fi-section-header-heading">Blade · reference</h2>
            <div class="icon-demo-preview">
                <x-filament::icon
                    :icon="$check"
                    role="img"
                    aria-label="Saved"
                />
                <span>Named SVG · Saved</span>
            </div>
            <div class="icon-demo-preview">
                @foreach (IconSize::cases() as $size)
                    <span class="icon-demo-sample">
                        <x-filament::icon
                            :icon="$check"
                            :size="$size"
                            aria-hidden="true"
                        />
                        <span>{{ $size->value }}</span>
                    </span>
                @endforeach
            </div>
            <div class="icon-demo-preview">
                <x-filament::icon
                    icon="/images/icon-check.svg"
                    alt="Saved image"
                />
                <span>Named image</span>
                <x-filament::icon :icon="$check" aria-hidden="true" />
                <span>Decorative beside text</span>
            </div>
            <p>
                The same shape, size hooks and theme CSS as the JavaScript
                widgets. SVG content uses Blade’s Htmlable wrapper; images use
                the URL branch.
            </p>
            <p>
                JavaScript widgets below use real Lucide components. The host
                chooses artwork; PHP icon aliases are not available in the
                browser. Image files have their own color, so the accent applies
                only to inline SVGs.
            </p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

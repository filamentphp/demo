<x-filament-widgets::widget>
    <x-filament::section>
        <div class="avatar-demo" data-framework="blade">
            <h2 class="fi-section-header-heading">Blade · reference</h2>
            <div class="avatar-demo-profile">
                <x-filament::avatar
                    src="/images/avatar-dan.png"
                    alt="Dan Harrin"
                    size="lg"
                />
                <div>
                    <strong>Dan Harrin</strong>
                    <p>Filament maintainer</p>
                </div>
            </div>
            <p>All four components use the same panel stylesheet.</p>
            <div class="avatar-demo-profile">
                <x-filament::avatar
                    src="/images/avatar-dan.png"
                    alt="Small portrait"
                    size="sm"
                />
                <x-filament::avatar
                    src="/images/avatar-dan.png"
                    alt="Medium portrait"
                />
                <x-filament::avatar
                    src="/images/avatar-dan.png"
                    alt="Large portrait"
                    size="lg"
                />
                <x-filament::avatar
                    src="/images/avatar-dan.png"
                    alt="Custom portrait"
                    size="avatar-demo-large"
                    :circular="false"
                    class="avatar-demo-accent"
                />
            </div>
            <p>Small, medium, large, and a custom theme class.</p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

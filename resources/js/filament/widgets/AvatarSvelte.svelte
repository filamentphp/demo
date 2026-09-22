<script>
    import Avatar from '../../../../vendor/filament/support/resources/js/svelte/Avatar.svelte'

    let size = $state('lg')
    let circular = $state(true)
    let accent = $state(false)
    let loaded = $state(false)

    function reset() {
        size = 'md'
        circular = true
        accent = false
    }
</script>

<div class="avatar-demo" data-framework="svelte">
    <h2 class="fi-section-header-heading">Svelte · JavaScript</h2>
    <div class="avatar-demo-profile">
        <Avatar
            src="/images/avatar-dan.png"
            alt="Dan Harrin"
            {size}
            {circular}
            class={accent ? 'avatar-demo-accent' : undefined}
            onload={() => (loaded = true)}
        />
        <div>
            <strong>Dan Harrin</strong>
            <p>Filament maintainer</p>
        </div>
    </div>
    <div class="avatar-demo-controls">
        <label
            >Size <select bind:value={size}
                ><option value="sm">Small</option><option value="md"
                    >Medium</option
                ><option value="lg">Large</option><option
                    value="avatar-demo-large">Custom</option
                ></select
            ></label
        >
        <label><input type="checkbox" bind:checked={circular} /> Circular</label
        >
        <label
            ><input type="checkbox" bind:checked={accent} /> Theme accent</label
        >
    </div>
    <button type="button" class="fi-btn fi-size-sm" onclick={reset}
        >Reset to defaults</button
    >
    <output data-loaded={loaded}
        >{loaded
            ? 'Image loaded · native event received'
            : 'Loading image'}</output
    >
</div>

<script>
    import { Check, X } from '@lucide/svelte'
    import Icon from '../../../../vendor/filament/support/resources/js/svelte/Icon.svelte'

    let size = $state('md')
    let saved = $state(true)
    let image = $state(false)
    let named = $state(true)
    let accent = $state(false)
    const Artwork = $derived(saved ? Check : X)
</script>

<div class="icon-demo" data-framework="svelte">
    <h2 class="fi-section-header-heading">Svelte · JavaScript + Lucide</h2>
    <div class="icon-demo-preview" data-testid="icon-preview">
        <Icon
            {size}
            src={image
                ? `/images/icon-${saved ? 'check' : 'x'}.svg`
                : undefined}
            alt={named ? (saved ? 'Saved' : 'Not saved') : ''}
            role={!image && named ? 'img' : undefined}
            aria-label={!image && named
                ? saved
                    ? 'Saved'
                    : 'Not saved'
                : undefined}
            aria-hidden={named ? undefined : true}
            class={accent ? 'icon-demo-accent' : undefined}
            data-state={saved ? 'saved' : 'unsaved'}
            ><Artwork aria-hidden="true" /></Icon
        >
        <span
            >{named ? 'Named' : 'Decorative'}
            {image ? 'image' : 'SVG'} · {saved ? 'Saved' : 'Not saved'}</span
        >
    </div>
    <div class="icon-demo-preview">
        {#each ['xs', 'sm', 'md', 'lg', 'xl', '2xl'] as size}<span
                class="icon-demo-sample"
                ><Icon {size} aria-hidden="true"><Check /></Icon><span
                    >{size}</span
                ></span
            >{/each}
    </div>
    <div class="icon-demo-controls">
        <label
            >Size <select bind:value={size}
                >{#each ['xs', 'sm', 'md', 'lg', 'xl', '2xl'] as size}<option
                        >{size}</option
                    >{/each}</select
            ></label
        >
        <label><input type="checkbox" bind:checked={image} /> Image</label>
        <label
            ><input type="checkbox" bind:checked={named} /> Accessible name</label
        >
        <label
            ><input type="checkbox" bind:checked={accent} /> Theme accent</label
        >
    </div>
    <div class="icon-demo-controls">
        <button
            type="button"
            class="fi-btn fi-size-sm"
            onclick={() => (saved = !saved)}>Toggle artwork</button
        >
        <button
            type="button"
            class="fi-btn fi-size-sm"
            onclick={() => {
                size = 'md'
                saved = true
                image = false
                named = true
                accent = false
            }}>Reset</button
        >
    </div>
</div>

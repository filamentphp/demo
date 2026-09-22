<script>
    import LoadingIndicator from '../../../../vendor/filament/support/resources/js/svelte/LoadingIndicator.svelte'

    let size = $state('md')
    let loading = $state(true)
    let accent = $state(false)
</script>

<div class="loading-indicator-demo" data-framework="svelte">
    <h2 class="fi-section-header-heading">Svelte · JavaScript</h2>
    <div role="status" class="loading-indicator-demo-status">
        {#if loading}<LoadingIndicator
                {size}
                class={accent ? 'loading-indicator-demo-accent' : undefined}
            />{/if}
        <span
            >{loading ? 'Preparing your report…' : 'Your report is ready'}</span
        >
    </div>
    <div class="loading-indicator-demo-controls">
        <label
            >Size <select bind:value={size}
                >{#each ['xs', 'sm', 'md', 'lg', 'xl', '2xl'] as size}<option
                        >{size}</option
                    >{/each}</select
            ></label
        >
        <label><input type="checkbox" bind:checked={loading} /> Loading</label>
        <label
            ><input type="checkbox" bind:checked={accent} /> Theme accent</label
        >
    </div>
    <button
        type="button"
        class="fi-btn fi-size-sm"
        onclick={() => {
            size = 'md'
            loading = true
            accent = false
        }}>Reset</button
    >
</div>

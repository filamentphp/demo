<script>
    import Actions from '../../../../vendor/filament/support/resources/js/svelte/Actions.svelte'
    let alignment = $state('start')
    let fullWidth = $state(false)
    let visible = $state(true)
    let name = $state('Community garden')
    let message = $state('No changes saved.')
</script>

<section class="actions-demo" data-framework="svelte">
    <h2 class="fi-section-header-heading">Svelte · JavaScript</h2>
    <form
        onsubmit={(event) => {
            event.preventDefault()
            message = `Saved ${name} locally.`
        }}
    >
        <label
            >Project name <input bind:value={name} data-testid="name" /></label
        >
        {#if visible}<Actions {alignment} {fullWidth} data-testid="actions">
                <button type="submit" data-testid="save">Save</button>
                <button
                    type="button"
                    data-testid="archive"
                    onclick={() => (message = `Archived ${name} locally.`)}
                    >Archive</button
                >
                <a href="#svelte-project">View project</a>
            </Actions>{/if}
    </form>
    <div class="icon-demo-controls">
        <label
            >Alignment <select bind:value={alignment} data-testid="alignment"
                >{#each ['start', 'left', 'center', 'end', 'right', 'between', 'justify'] as value}<option
                        >{value}</option
                    >{/each}</select
            ></label
        >
        <label
            ><input
                bind:checked={fullWidth}
                type="checkbox"
                data-testid="full-width"
            />Full width</label
        >
        <label
            ><input
                bind:checked={visible}
                type="checkbox"
                data-testid="visible"
            />Show actions</label
        >
        <button
            type="button"
            data-testid="reset"
            onclick={() => {
                alignment = 'start'
                fullWidth = false
                visible = true
                name = 'Community garden'
                message = 'No changes saved.'
            }}>Reset</button
        >
    </div>
    <p role="status" data-testid="result">{message}</p>
    <p id="svelte-project" tabindex="-1">Community garden · Project details</p>
</section>

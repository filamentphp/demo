<script>
    import { Star } from '@lucide/svelte'
    import Badge from '../../../../vendor/filament/support/resources/js/svelte/Badge.svelte'
    let loading = $state(false)
    let disabled = $state(false)
    let enhanced = $state(true)
    let visible = $state(true)
    let deleting = $state(false)
    let removed = $state(false)
    let count = $state(0)
    function finish() {
        loading = false
        if (deleting) {
            removed = true
            deleting = false
        }
    }
    function reset() {
        loading = deleting = removed = disabled = false
        enhanced = visible = true
        count = 0
    }
</script>

<div class="callout-demo" data-framework="svelte">
    <h2 class="fi-section-header-heading">Svelte · Project labels</h2>
    <p>
        Local-only project filters. Alt+S saves; Finish completes pending work.
    </p>
    <div class="radio-demo-controls" data-testid="palette">
        <Badge>New</Badge><Badge color="gray" size="xs">Archived</Badge><Badge
            color="brand"
            size="sm">Priority{#snippet icon()}<Star />{/snippet}</Badge
        ><Badge color="success" iconPosition="after"
            >Approved{#snippet icon()}<Star />{/snippet}</Badge
        >
    </div>
    {#if visible}<div
            class="radio-demo-controls"
            data-testid="interactive-badges"
        >
            <Badge
                tag="a"
                href="#svelte-projects"
                data-testid="project-link"
                {disabled}
                tooltip={enhanced ? 'Jump to the project summary' : undefined}
                >View projects</Badge
            >
            <Badge
                tag="button"
                type="submit"
                form="svelte-badge-form"
                data-testid="save-badge"
                color="brand"
                {disabled}
                {loading}
                tooltip={enhanced ? 'Save project filters (Alt+S)' : undefined}
                keyBindings={enhanced ? ['alt+s'] : undefined}
                >{loading
                    ? 'Saving filters'
                    : 'Save filters'}{#snippet icon()}<Star />{/snippet}</Badge
            >
            {#if !removed}<Badge
                    data-testid="delete-badge"
                    color="warning"
                    {disabled}
                    onDelete={() => (deleting = true)}
                    deleteLoading={deleting}
                    deleteLabel="Remove priority filter">Priority filter</Badge
                >{/if}
        </div>{/if}
    <form
        id="svelte-badge-form"
        onsubmit={(event) => {
            event.preventDefault()
            loading = true
            count++
        }}
    >
        <label
            >Filter name <input
                data-testid="filter-name"
                value="Community garden"
            /></label
        >
    </form>
    <div class="icon-demo-controls">
        <button type="button" data-testid="finish" onclick={finish}
            >Finish</button
        ><button type="button" data-testid="reset" onclick={reset}>Reset</button
        >
    </div>
    <label
        ><input
            type="checkbox"
            data-testid="disabled"
            bind:checked={disabled}
        /> Disable actions</label
    >
    <label
        ><input
            type="checkbox"
            data-testid="enhanced"
            bind:checked={enhanced}
        /> Tooltips and shortcut</label
    >
    <label
        ><input type="checkbox" data-testid="visible" bind:checked={visible} /> Mount
        interactive badges</label
    >
    <p id="svelte-projects">Community garden · 12 projects</p>
    <p role="status" data-testid="result">
        {count} saves requested. {removed
            ? 'Priority filter removed.'
            : deleting
              ? 'Removing priority filter…'
              : loading
                ? 'Saving filters…'
                : 'Ready.'}
    </p>
</div>

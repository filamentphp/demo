<script>
    import { FolderPlus, Search } from '@lucide/svelte'
    import EmptyState from '../../../../vendor/filament/support/resources/js/svelte/EmptyState.svelte'
    let filtered = $state(false)
    let minimal = $state(false)
    let created = $state(0)
    let message = $state('Your workspace is ready.')
</script>

{#snippet icon()}{#if filtered}<Search aria-hidden="true" />{:else}<FolderPlus
            aria-hidden="true"
        />{/if}{/snippet}
{#snippet description()}Make room for your next <strong>great idea</strong
    >.{/snippet}
{#snippet footer()}
    <div class="radio-demo-controls">
        <button
            type="button"
            data-testid="create"
            onclick={() => {
                created++
                message = 'Project draft created locally.'
            }}>Create project</button
        >
        <a href="#svelte-projects">View workspace</a>
    </div>
{/snippet}
<section class="radio-demo" data-framework="svelte">
    <h2 class="fi-section-header-heading">Svelte · JavaScript</h2>
    <EmptyState
        data-testid="empty-state"
        title={filtered ? 'Filtered projects' : 'All projects'}
        heading={filtered ? 'No matching projects' : 'No projects yet'}
        headingTag="h3"
        description={minimal ? '  ' : description}
        icon={minimal ? undefined : icon}
        footer={minimal ? undefined : footer}
        iconColor={filtered ? 'gray' : 'primary'}
        iconSize={filtered ? 'sm' : 'lg'}
        compact={filtered}
        contained={!filtered}
    />
    <div class="radio-demo-controls">
        <button
            type="button"
            data-testid="filter"
            onclick={() => (filtered = !filtered)}>Toggle filtered view</button
        >
        <button
            type="button"
            data-testid="minimal"
            onclick={() => (minimal = !minimal)}>Toggle details</button
        >
        <button
            type="button"
            data-testid="reset"
            onclick={() => {
                filtered = false
                minimal = false
                created = 0
                message = 'Your workspace is ready.'
            }}>Reset</button
        >
    </div>
    <p role="status" data-testid="result">{message} Drafts: {created}</p>
    <div id="svelte-projects" tabindex="-1">
        Workspace · {created
            ? `${created} project draft${created === 1 ? '' : 's'}`
            : 'No saved projects'}
    </div>
</section>

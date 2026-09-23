<script>
    import { Info, CircleCheck } from '@lucide/svelte'
    import Callout from '../../../../vendor/filament/support/resources/js/svelte/Callout.svelte'
    let color = $state('gray')
    let icon = $state('none')
    let override = $state(false)
    let details = $state(true)
    let actions = $state(true)
    let visible = $state(true)
    let message = $state('No changes published.')
</script>

{#snippet artwork()}{#if icon === 'info'}<Info />{:else}<CircleCheck
        />{/if}{/snippet}
{#snippet description()}Review the <strong>release notes</strong> before publishing.{/snippet}
{#snippet footer()}<button
        type="button"
        data-testid="review"
        onclick={() => (message = 'Release notes reviewed locally.')}
        >Review notes</button
    ><a href="#svelte-release">View release</a>{/snippet}
{#snippet controls()}<button
        type="button"
        data-testid="dismiss"
        aria-label="Dismiss release notice"
        onclick={() => {
            visible = false
            message = 'Release notice dismissed locally.'
        }}>×</button
    >{/snippet}
<section class="callout-demo" data-framework="svelte">
    <h2 class="fi-section-header-heading">Svelte · JavaScript</h2>
    {#if visible}<Callout
            data-testid="callout"
            {color}
            heading="Your next release"
            description={details ? description : undefined}
            icon={icon === 'none' ? undefined : artwork}
            footer={actions ? footer : undefined}
            controls={actions ? controls : undefined}
            iconColor={override ? 'success' : undefined}
            iconSize={override ? 'sm' : 'lg'}
        />{/if}
    <div class="icon-demo-controls">
        <label
            >Color <select bind:value={color} data-testid="color"
                >{#each ['gray', 'primary', 'info', 'success', 'warning', 'danger', 'brand'] as value}<option
                        >{value}</option
                    >{/each}</select
            ></label
        >
        <label
            >Icon <select bind:value={icon} data-testid="icon"
                >{#each ['none', 'info', 'check'] as value}<option
                        >{value}</option
                    >{/each}</select
            ></label
        >
        <label
            ><input
                bind:checked={override}
                type="checkbox"
                data-testid="override"
            />Small success icon</label
        >
        <label
            ><input
                bind:checked={details}
                type="checkbox"
                data-testid="details"
            />Description</label
        >
        <label
            ><input
                bind:checked={actions}
                type="checkbox"
                data-testid="actions"
            />Actions and controls</label
        >
        <button
            type="button"
            data-testid="reset"
            onclick={() => {
                color = 'gray'
                icon = 'none'
                override = false
                details = true
                actions = true
                visible = true
                message = 'No changes published.'
            }}>Reset</button
        >
    </div>
    <p role="status" data-testid="result">{message}</p>
    <p id="svelte-release" tabindex="-1">
        September release · Ready for review
    </p>
</section>

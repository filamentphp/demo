<script>
    import Fieldset from '../../../../vendor/filament/support/resources/js/svelte/Fieldset.svelte'

    let options = $state({})
    let delivery = $state(false)
    const controls = {
        uncontained: 'Uncontained',
        labelHidden: 'Hide legend',
        required: 'Required mark',
        disabled: 'Disabled',
        noLabel: 'No legend',
        rtl: 'RTL',
        accent: 'Theme accent',
        richLabel: 'Rich legend',
    }
</script>

{#snippet richLabel()}<em>Delivery preferences</em>{/snippet}

<div class="fieldset-demo" data-framework="svelte">
    <h2 class="fi-section-header-heading">Svelte · JavaScript</h2>
    <Fieldset
        label={options.noLabel
            ? null
            : options.richLabel
              ? richLabel
              : 'Delivery preferences'}
        contained={!options.uncontained}
        labelHidden={!!options.labelHidden}
        required={!!options.required}
        disabled={!!options.disabled}
        dir={options.rtl ? 'rtl' : 'ltr'}
        class={options.accent ? 'fieldset-demo-accent' : undefined}
    >
        <label class="fieldset-demo-option"
            ><input type="checkbox" bind:checked={delivery} /> Leave the parcel at
            reception</label
        >
    </Fieldset>
    <div class="fieldset-demo-controls">
        {#each Object.entries(controls) as [name, label]}
            <label
                ><input type="checkbox" bind:checked={options[name]} />
                {label}</label
            >
        {/each}
    </div>
    <button
        type="button"
        class="fi-btn fi-size-sm"
        onclick={() => {
            options = {}
            delivery = false
        }}>Reset</button
    >
    <output>{delivery ? 'Leave at reception' : 'Hand to recipient'}</output>
</div>

<script>
    import { PoundSterling } from '@lucide/svelte'
    import InputWrapper from '../../../../vendor/filament/support/resources/js/svelte/InputWrapper.svelte'
    import Icon from '../../../../vendor/filament/support/resources/js/svelte/Icon.svelte'
    let amount = $state(125)
    let disabled = $state(false)
    let invalid = $state(false)
    let affixes = $state(true)
    let icons = $state(false)
    let inline = $state(false)
    let actions = $state(false)
    let input = $state()
    function reset() {
        amount = 125
        disabled = false
        invalid = false
        affixes = true
        icons = false
        inline = false
        actions = false
    }
</script>

{#snippet icon()}<Icon aria-hidden="true"><PoundSterling /></Icon>{/snippet}
{#snippet action()}<button
        type="button"
        {disabled}
        onclick={() => (amount = undefined)}>Clear</button
    >{/snippet}
<div class="icon-demo" data-framework="svelte">
    <h2 class="fi-section-header-heading">Svelte · JavaScript</h2>
    <label for="svelte-price">Workshop price</label>
    <InputWrapper
        {disabled}
        valid={!invalid}
        prefix={affixes ? '£' : null}
        suffix={affixes ? 'GBP' : null}
        inlinePrefix={inline}
        inlineSuffix={inline}
        prefixIcon={icons ? icon : undefined}
        suffixActions={actions ? action : undefined}
    >
        <input
            bind:this={input}
            id="svelte-price"
            class="fi-input"
            type="number"
            min="1"
            required
            bind:value={amount}
            {disabled}
            aria-invalid={invalid}
            aria-describedby={invalid ? 'svelte-price-error' : undefined}
        />
    </InputWrapper>
    {#if invalid}<p id="svelte-price-error" role="status">
            Enter a workshop price of at least £1.
        </p>{/if}
    <div class="icon-demo-controls">
        <label><input type="checkbox" bind:checked={disabled} /> Disabled</label
        >
        <label><input type="checkbox" bind:checked={invalid} /> Invalid</label>
        <label
            ><input type="checkbox" bind:checked={affixes} /> Text affixes</label
        >
        <label><input type="checkbox" bind:checked={icons} /> Icon</label>
        <label><input type="checkbox" bind:checked={inline} /> Inline</label>
        <label
            ><input type="checkbox" bind:checked={actions} /> Clear action</label
        >
    </div>
    <div class="icon-demo-controls">
        <button
            type="button"
            class="fi-btn fi-size-sm"
            onclick={() => (invalid = !input.checkValidity())}>Validate</button
        >
        <button type="button" class="fi-btn fi-size-sm" onclick={reset}
            >Reset</button
        >
    </div>
    <p>
        Native input value: {amount ?? 'empty'}. Wrapper styling and input
        semantics are supplied separately.
    </p>
</div>

<script>
    import Radio from '../../../../vendor/filament/support/resources/js/svelte/Radio.svelte'
    let delivery = $state('standard')
    let disabled = $state(false)
    let invalid = $state(false)
    let required = $state(false)
    let submitted = $state('Not previewed')
</script>

<div class="radio-demo" data-framework="svelte">
    <h2 class="fi-section-header-heading">Svelte · JavaScript</h2>
    <form
        onsubmit={(event) => {
            event.preventDefault()
            submitted = JSON.stringify([...new FormData(event.currentTarget)])
        }}
        onreset={() => {
            delivery = 'standard'
            submitted = 'Not previewed'
        }}
    >
        <fieldset>
            <legend>Delivery method</legend>
            {#each ['standard', 'express'] as value (value)}
                <label>
                    <Radio
                        bind:group={delivery}
                        name="delivery"
                        {value}
                        {disabled}
                        valid={!invalid}
                        {required}
                        aria-invalid={invalid}
                    />
                    {value === 'standard'
                        ? 'Standard · 3–5 working days'
                        : 'Express · next working day'}
                </label>
            {/each}
        </fieldset>
        <div class="radio-demo-controls">
            <button type="submit" class="fi-btn fi-size-sm"
                >Preview FormData</button
            >
            <button type="reset" class="fi-btn fi-size-sm"
                >Reset delivery</button
            >
            <button
                type="button"
                class="fi-btn fi-size-sm"
                onclick={() => (delivery = null)}>Clear selection</button
            >
        </div>
    </form>
    <div class="radio-demo-controls">
        <label><input type="checkbox" bind:checked={disabled} /> Disabled</label
        >
        <label><input type="checkbox" bind:checked={invalid} /> Invalid</label>
        <label><input type="checkbox" bind:checked={required} /> Required</label
        >
    </div>
    <output>{delivery ?? 'No selection'} · {submitted}</output>
    <p>Preview stays in this browser. No order is placed.</p>
</div>

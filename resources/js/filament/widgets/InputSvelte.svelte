<script>
    import Input from '../../../../vendor/filament/support/resources/js/svelte/Input.svelte'
    import InputWrapper from '../../../../vendor/filament/support/resources/js/svelte/InputWrapper.svelte'
    let price = $state(0)
    let disabled = $state(false)
    let readOnly = $state(false)
    let inline = $state(false)
    let result = $state('No submission yet')
    let form = $state()
</script>

<div class="radio-demo" data-framework="svelte">
    <h2 class="fi-section-header-heading">Svelte · JavaScript</h2>
    <form
        bind:this={form}
        onsubmit={(event) => {
            event.preventDefault()
            result = JSON.stringify(
                Object.fromEntries(new FormData(event.currentTarget)),
            )
        }}
        onreset={() => (result = 'Reset to defaults')}
    >
        <label for="svelte-title">Workshop title (uncontrolled)</label>
        <InputWrapper
            ><Input
                id="svelte-title"
                name="title"
                defaultValue="Botanical drawing"
                required
            /></InputWrapper
        >
        <label for="svelte-email">Contact email (uncontrolled)</label>
        <InputWrapper
            ><Input
                id="svelte-email"
                name="email"
                type="email"
                defaultValue="ada@example.com"
                required
            /></InputWrapper
        >
        <label for="svelte-price">Ticket price (bound)</label>
        <InputWrapper
            prefix="£"
            suffix="GBP"
            inlinePrefix={inline}
            inlineSuffix={inline}
            {disabled}
        >
            <Input
                id="svelte-price"
                name="price"
                type="number"
                min="0"
                step="0.5"
                required
                bind:value={price}
                defaultValue={0}
                {disabled}
                readonly={readOnly}
                inlinePrefix={inline}
                inlineSuffix={inline}
            />
        </InputWrapper>
        <div class="radio-demo-controls">
            <button type="submit">Submit locally</button><button type="reset"
                >Reset form</button
            >
        </div>
    </form>
    <div class="radio-demo-controls">
        <label
            ><input
                type="checkbox"
                data-testid="disabled"
                bind:checked={disabled}
            /> Disabled price</label
        >
        <label
            ><input
                type="checkbox"
                data-testid="readonly"
                bind:checked={readOnly}
            /> Read-only price</label
        >
        <label
            ><input
                type="checkbox"
                data-testid="inline"
                bind:checked={inline}
            /> Inline affixes</label
        >
        <button
            type="button"
            data-testid="empty"
            onclick={() => (price = undefined)}>Empty price</button
        >
        <button type="button" data-testid="zero" onclick={() => (price = 0)}
            >Zero price</button
        >
        <button
            type="button"
            data-testid="validate"
            onclick={() =>
                (result = form.reportValidity() ? 'Valid' : 'Invalid')}
            >Validate</button
        >
    </div>
    <p data-testid="state">
        Host price: {JSON.stringify(price) ?? 'undefined'} ({typeof price})
    </p>
    <output data-testid="result">{result}</output>
    <p>
        Svelte binds numbers, or undefined when empty. Native reset restores
        defaultValue and synchronizes the bound state.
    </p>
</div>

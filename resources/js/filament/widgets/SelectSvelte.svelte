<script>
    import Select from '../../../../vendor/filament/support/resources/js/svelte/Select.svelte'
    import InputWrapper from '../../../../vendor/filament/support/resources/js/svelte/InputWrapper.svelte'
    let workshop = $state('drawing')
    let extras = $state(['ceramics'])
    let disabled = $state(false)
    let inline = $state(false)
    let remove = $state(false)
    let result = $state('No submission yet')
    let form = $state()
    const options = $derived(
        [
            ['drawing', 'Botanical drawing'],
            ['ceramics', 'Studio ceramics'],
            ['printing', 'Printmaking'],
            ['archived', 'Watercolours (unavailable)'],
        ].filter(([value]) => !remove || value !== 'drawing'),
    )
</script>

<div class="radio-demo" data-framework="svelte">
    <h2 class="fi-section-header-heading">Svelte · JavaScript</h2>
    <form
        bind:this={form}
        onsubmit={(event) => {
            event.preventDefault()
            result = JSON.stringify([...new FormData(event.currentTarget)])
        }}
        onreset={() => (result = 'Reset to defaults')}
    >
        <label for="svelte-workshop">Main workshop</label>
        <InputWrapper prefix="Art" inlinePrefix={inline} {disabled}>
            <Select
                id="svelte-workshop"
                data-testid="workshop"
                name="workshop"
                required
                bind:value={workshop}
                defaultValue="drawing"
                {disabled}
                inlinePrefix={inline}
            >
                <option value="">Choose a workshop</option>
                {#each options as [value, label] (value)}<option
                        {value}
                        disabled={value === 'archived'}>{label}</option
                    >{/each}
            </Select>
        </InputWrapper>
        <label for="svelte-extras">Additional workshops (multiple)</label>
        <InputWrapper {disabled}>
            <Select
                id="svelte-extras"
                data-testid="extras"
                name="extras"
                multiple
                size={4}
                bind:value={extras}
                defaultValue={['ceramics']}
                {disabled}
            >
                {#each options as [value, label] (value)}<option
                        {value}
                        disabled={value === 'archived'}>{label}</option
                    >{/each}
            </Select>
        </InputWrapper>
        <label for="svelte-delivery">Ticket delivery (internally managed)</label
        >
        <InputWrapper
            ><Select
                id="svelte-delivery"
                name="delivery"
                data-testid="delivery"
                defaultValue="email"
                ><option value="email">Email tickets</option><option
                    value="desk">Collect at reception</option
                ></Select
            ></InputWrapper
        >
        <div class="radio-demo-controls">
            <button type="submit" data-testid="submit">Submit locally</button
            ><button type="reset" data-testid="reset">Reset form</button>
        </div>
    </form>
    <div class="radio-demo-controls">
        <label
            ><input
                type="checkbox"
                data-testid="disabled"
                bind:checked={disabled}
            /> Disable workshops</label
        >
        <label
            ><input
                type="checkbox"
                data-testid="inline"
                bind:checked={inline}
            /> Inline prefix</label
        >
        <label
            ><input
                type="checkbox"
                data-testid="remove"
                bind:checked={remove}
            /> Remove drawing option</label
        >
        <button
            type="button"
            data-testid="empty"
            onclick={() => {
                workshop = ''
                extras = []
            }}>Clear selections</button
        >
        <button
            type="button"
            data-testid="archived"
            onclick={() => {
                workshop = 'archived'
                extras = ['printing', 'archived']
            }}>Select unavailable option</button
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
        Selection: {JSON.stringify(workshop)}; extras: {JSON.stringify(extras)}
    </p>
    <output data-testid="result">{result}</output>
    <p>
        Svelte 5.57 synchronizes bound state on native reset. Removing an option
        preserves host state but displays no selection. Multiple values are
        preserved in submission.
    </p>
</div>

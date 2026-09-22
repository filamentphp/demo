<script>
    import Checkbox from '../../../../vendor/filament/support/resources/js/svelte/Checkbox.svelte'
    let checked = $state(true)
    let disabled = $state(false)
    let invalid = $state(false)
    let required = $state(false)
    let submitted = $state('Not submitted')
</script>

<div class="checkbox-demo" data-framework="svelte">
    <h2 class="fi-section-header-heading">Svelte · JavaScript</h2>
    <form
        onsubmit={(event) => {
            event.preventDefault()
            submitted = JSON.stringify([...new FormData(event.currentTarget)])
        }}
    >
        <label
            ><Checkbox
                bind:checked
                name="digest"
                value="weekly"
                {disabled}
                valid={!invalid}
                {required}
                aria-invalid={invalid}
            /> Send me the weekly digest</label
        >
        <button type="submit" class="fi-btn fi-size-sm"
            >Preview submission</button
        >
    </form>
    <div class="checkbox-demo-controls">
        <label><input type="checkbox" bind:checked={disabled} /> Disabled</label
        >
        <label><input type="checkbox" bind:checked={invalid} /> Invalid</label>
        <label><input type="checkbox" bind:checked={required} /> Required</label
        >
    </div>
    <output>{checked ? 'Subscribed' : 'Not subscribed'} · {submitted}</output>
    <button
        type="button"
        class="fi-btn fi-size-sm"
        onclick={() => {
            checked = true
            disabled = false
            invalid = false
            required = false
            submitted = 'Not submitted'
        }}>Reset</button
    >
</div>

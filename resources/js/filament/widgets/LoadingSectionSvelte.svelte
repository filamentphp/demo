<script>
    import LoadingSection from '../../../../vendor/filament/support/resources/js/svelte/LoadingSection.svelte'
    let loadingState = $state('loading')
    let tall = $state(false)
    let visible = $state(true)
    let gridOffset = $state(false)
</script>

<section class="fi-section">
    <div class="fi-section-content">
        <div class="actions-demo" data-framework="svelte">
            <h2 class="fi-section-header-heading">Svelte · Project overview</h2>
            <p>
                Load the latest community garden milestones. Finish manually to
                explore success and failure.
            </p>
            <div
                class="fi-grid"
                data-testid="preview-grid"
                style="--cols-default: repeat(4, minmax(0, 1fr))"
            >
                {#if visible}
                    {#if loadingState === 'loading'}<LoadingSection
                            data-testid="loading-section"
                            height={tall ? '12rem' : undefined}
                            loadingLabel="Loading project overview"
                            columnSpan={gridOffset
                                ? { default: 'full', lg: 2 }
                                : { default: 'full' }}
                            columnStart={gridOffset ? { lg: 3 } : undefined}
                        />
                    {:else}<div
                            class="fi-section fi-grid-col"
                            style="--col-span-default: 1 / -1"
                            data-testid="content"
                        >
                            <div class="fi-section-content">
                                {loadingState === 'ready'
                                    ? 'Community garden · 8 of 12 milestones complete.'
                                    : 'Project overview could not be loaded. Try again.'}
                            </div>
                        </div>{/if}
                {/if}
            </div>
            <div class="icon-demo-controls">
                <button
                    type="button"
                    data-testid="load"
                    disabled={loadingState === 'loading'}
                    onclick={() => (loadingState = 'loading')}
                    >Load overview</button
                >
                <button
                    type="button"
                    data-testid="finish"
                    disabled={loadingState !== 'loading'}
                    onclick={() => (loadingState = 'ready')}
                    >Finish successfully</button
                >
                <button
                    type="button"
                    data-testid="fail"
                    disabled={loadingState !== 'loading'}
                    onclick={() => (loadingState = 'error')}
                    >Simulate error</button
                >
                <button
                    type="button"
                    data-testid="reset"
                    onclick={() => {
                        loadingState = 'loading'
                        tall = false
                        visible = true
                        gridOffset = false
                    }}>Reset</button
                >
            </div>
            <label
                ><input
                    bind:checked={tall}
                    type="checkbox"
                    data-testid="tall"
                /> Taller placeholder</label
            >
            <label
                ><input
                    bind:checked={visible}
                    type="checkbox"
                    data-testid="visible"
                /> Show preview</label
            >
            <p role="status" data-testid="result">
                {loadingState === 'ready'
                    ? 'Project overview is ready.'
                    : loadingState === 'error'
                      ? 'Loading failed. You can retry.'
                      : ''}
            </p>
            <label
                ><input
                    bind:checked={gridOffset}
                    type="checkbox"
                    data-testid="grid-offset"
                /> Use columns 3–4 on wide screens (1024px+)</label
            >
        </div>
    </div>
</section>

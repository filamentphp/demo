<script>
    import { Deferred, Form, Link, router, useForm, useHttp } from '@inertiajs/svelte'

    let {
        section,
        report,
        analytics,
        permissions,
        requestToken,
        catalog,
        optionalSummary,
        contacts,
        savedDraft,
        endpoints,
    } = $props()

    let notes = $state('Follow up with the customer success team.')
    let httpResult = $state('Not submitted')
    const form = useForm('WorkbenchDraft', { title: '' })
    const http = useHttp({ title: '' })

    function loadContacts(reset = false) {
        router.reload({
            data: { batch: reset ? 1 : 2 },
            only: ['contacts'],
            reset: reset ? ['contacts'] : [],
        })
    }
</script>

<svelte:head>
    <title>{report.title} — Inertia workbench</title>
</svelte:head>

<section class="fi-inertia-workbench" aria-label="Inertia report">
    <p class="fi-inertia-eyebrow">Rendered by Svelte · navigated by Livewire</p>
    <nav aria-label="Report sections">
        <Link href={endpoints.overview} preserveState aria-current={section === 'overview' ? 'page' : undefined}>
            Overview
        </Link>
        <Link href={endpoints.activity} preserveState aria-current={section === 'activity' ? 'page' : undefined}>
            Activity
        </Link>
    </nav>
    <h2 id="inertia-report-title">{report.title}</h2>
    <p>{report.description}</p>
    <label for="report-notes">Working notes (Svelte state)</label>
    <textarea id="report-notes" bind:value={notes}></textarea>
    <p>Notes should survive a Livewire shell refresh.</p>
</section>

<section class="fi-inertia-workbench" aria-labelledby="inertia-props-heading">
    <h2 id="inertia-props-heading">Inertia 3 prop experiments</h2>
    <p>Current content section: <strong id="content-section">{section}</strong></p>
    <Deferred data="analytics.total">
        {#snippet fallback()}<p>Loading analytics…</p>{/snippet}
        <p id="deferred-analytics">Deferred total: {analytics?.total}</p>
    </Deferred>
    <Deferred data="permissions">
        {#snippet fallback()}<p>Loading permissions…</p>{/snippet}
        <p id="deferred-permissions">Deferred once: {permissions?.join(', ')}</p>
    </Deferred>
    <p>Always-included request token: <output id="request-token">{requestToken}</output></p>
    <p>Once-only catalog token: <output id="catalog-token">{catalog.token}</output></p>
    <div class="fi-inertia-controls">
        <button id="load-optional" type="button" onclick={() => router.reload({ only: ['optionalSummary', 'analytics.audit'] })}>
            Load optional props
        </button>
        <button id="refresh-catalog" type="button" onclick={() => router.reload({ only: ['catalog'] })}>
            Refresh once-only catalog
        </button>
    </div>
    <p id="optional-summary">{optionalSummary ?? 'Optional summary not requested'}</p>
    <p id="optional-audit">{analytics?.audit ?? 'Nested audit not requested'}</p>

    <h3>Merging by contact ID</h3>
    <ul id="merged-contacts">
        {#each contacts.data as contact (contact.id)}
            <li>{contact.id}: {contact.name}</li>
        {/each}
    </ul>
    <div class="fi-inertia-controls">
        <button id="merge-contacts" type="button" onclick={() => loadContacts()}>Merge next batch</button>
        <button id="reset-contacts" type="button" onclick={() => loadContacts(true)}>Reset contacts</button>
    </div>
</section>

<section class="fi-inertia-workbench" aria-labelledby="inertia-forms-heading">
    <h2 id="inertia-forms-heading">Forms without Livewire submissions</h2>
    <p>Saved in this demo session: <strong id="saved-draft">{savedDraft.title}</strong></p>
    <form onsubmit={(event) => { event.preventDefault(); form.patch(endpoints.draft, { preserveScroll: true }) }}>
        <label for="draft-title">Remembered draft title (useForm)</label>
        <input
            id="draft-title"
            bind:value={form.title}
            aria-invalid={Boolean(form.errors.title)}
            aria-describedby={form.errors.title ? 'draft-error' : undefined}
        />
        {#if form.errors.title}<p id="draft-error" role="alert">{form.errors.title}</p>{/if}
        <div class="fi-inertia-controls">
            <button id="save-draft" type="submit" disabled={form.processing}>Save draft via PATCH</button>
            <button id="reset-draft" type="button" onclick={() => form.resetAndClearErrors()}>Reset draft</button>
        </div>
        <p id="draft-status">{form.processing ? 'Saving' : form.wasSuccessful ? 'Saved successfully' : 'Ready'}</p>
    </form>

    <Form action={endpoints.draft} method="post" resetOnSuccess options={{ preserveScroll: true }}>
        {#snippet children({ errors, processing, wasSuccessful })}
            <label for="component-title">Draft title (Form component)</label>
            <input
                id="component-title"
                name="title"
                aria-invalid={Boolean(errors.title)}
                aria-describedby={errors.title ? 'component-error' : undefined}
            />
            {#if errors.title}<p id="component-error" role="alert">{errors.title}</p>{/if}
            <button id="save-component" type="submit" disabled={processing}>Save with Form component</button>
            {#if wasSuccessful}<p id="component-success">Form component saved</p>{/if}
        {/snippet}
    </Form>

    <form onsubmit={(event) => {
        event.preventDefault()
        http.post(endpoints.http, { onSuccess: (response) => { httpResult = response.title } })
    }}>
        <label for="http-title">Standalone HTTP title (useHttp)</label>
        <input
            id="http-title"
            bind:value={http.title}
            aria-invalid={Boolean(http.errors.title)}
            aria-describedby={http.errors.title ? 'http-error' : undefined}
        />
        {#if http.errors.title}<p id="http-error" role="alert">{http.errors.title}</p>{/if}
        <button id="save-http" type="submit" disabled={http.processing}>Send JSON request</button>
        <p id="http-result">{httpResult}</p>
    </form>
</section>

<section class="fi-inertia-workbench" aria-labelledby="inertia-boundary-heading">
    <h2 id="inertia-boundary-heading">Navigation boundary probe</h2>
    <Link href="/">Back to dashboard</Link>
</section>

import {
    Deferred,
    Form,
    Head,
    Link,
    router,
    useForm,
    useHttp,
} from '@inertiajs/react'
import { useState } from 'react'

export default function ReactWorkbench(props) {
    const [notes, setNotes] = useState(
        'Follow up with the customer success team.',
    )
    const [httpResult, setHttpResult] = useState('Not submitted')
    const form = useForm('WorkbenchDraft', { title: '' })
    const http = useHttp({ title: '' })

    function loadContacts(reset = false) {
        router.reload({
            data: { batch: reset ? 1 : 2 },
            only: ['contacts'],
            reset: reset ? ['contacts'] : [],
        })
    }

    return (
        <>
            <Head title={`${props.report.title} — Inertia workbench`} />

            <section className="fi-inertia-workbench" aria-label="Inertia report">
                <p className="fi-inertia-eyebrow">
                    Rendered by React · navigated by Livewire
                </p>
                <nav aria-label="Report sections">
                    <Link
                        href={props.endpoints.overview}
                        preserveState
                        aria-current={props.section === 'overview' ? 'page' : undefined}
                    >
                        Overview
                    </Link>
                    <Link
                        href={props.endpoints.activity}
                        preserveState
                        aria-current={props.section === 'activity' ? 'page' : undefined}
                    >
                        Activity
                    </Link>
                </nav>
                <h2 id="inertia-report-title">{props.report.title}</h2>
                <p>{props.report.description}</p>
                <label htmlFor="report-notes">Working notes (React state)</label>
                <textarea
                    id="report-notes"
                    value={notes}
                    onChange={(event) => setNotes(event.target.value)}
                />
                <p>Notes should survive a Livewire shell refresh.</p>
            </section>

            <section className="fi-inertia-workbench" aria-labelledby="inertia-props-heading">
                <h2 id="inertia-props-heading">Inertia 3 prop experiments</h2>
                <p>
                    Current content section: <strong id="content-section">{props.section}</strong>
                </p>
                <Deferred data="analytics.total" fallback={<p>Loading analytics…</p>}>
                    <p id="deferred-analytics">Deferred total: {props.analytics?.total}</p>
                </Deferred>
                <Deferred data="permissions" fallback={<p>Loading permissions…</p>}>
                    <p id="deferred-permissions">Deferred once: {props.permissions?.join(', ')}</p>
                </Deferred>
                <p>
                    Always-included request token:{' '}
                    <output id="request-token">{props.requestToken}</output>
                </p>
                <p>
                    Once-only catalog token:{' '}
                    <output id="catalog-token">{props.catalog.token}</output>
                </p>
                <div className="fi-inertia-controls">
                    <button
                        id="load-optional"
                        type="button"
                        onClick={() => router.reload({ only: ['optionalSummary', 'analytics.audit'] })}
                    >
                        Load optional props
                    </button>
                    <button
                        id="refresh-catalog"
                        type="button"
                        onClick={() => router.reload({ only: ['catalog'] })}
                    >
                        Refresh once-only catalog
                    </button>
                </div>
                <p id="optional-summary">{props.optionalSummary ?? 'Optional summary not requested'}</p>
                <p id="optional-audit">{props.analytics?.audit ?? 'Nested audit not requested'}</p>

                <h3>Merging by contact ID</h3>
                <ul id="merged-contacts">
                    {props.contacts.data.map((contact) => (
                        <li key={contact.id}>{contact.id}: {contact.name}</li>
                    ))}
                </ul>
                <div className="fi-inertia-controls">
                    <button id="merge-contacts" type="button" onClick={() => loadContacts()}>
                        Merge next batch
                    </button>
                    <button id="reset-contacts" type="button" onClick={() => loadContacts(true)}>
                        Reset contacts
                    </button>
                </div>
            </section>

            <section className="fi-inertia-workbench" aria-labelledby="inertia-forms-heading">
                <h2 id="inertia-forms-heading">Forms without Livewire submissions</h2>
                <p>
                    Saved in this demo session:{' '}
                    <strong id="saved-draft">{props.savedDraft.title}</strong>
                </p>
                <form
                    onSubmit={(event) => {
                        event.preventDefault()
                        form.patch(props.endpoints.draft, { preserveScroll: true })
                    }}
                >
                    <label htmlFor="draft-title">Remembered draft title (useForm)</label>
                    <input
                        id="draft-title"
                        value={form.data.title}
                        onChange={(event) => form.setData('title', event.target.value)}
                        aria-invalid={Boolean(form.errors.title)}
                        aria-describedby={form.errors.title ? 'draft-error' : undefined}
                    />
                    {form.errors.title && <p id="draft-error" role="alert">{form.errors.title}</p>}
                    <div className="fi-inertia-controls">
                        <button id="save-draft" type="submit" disabled={form.processing}>
                            Save draft via PATCH
                        </button>
                        <button id="reset-draft" type="button" onClick={() => form.resetAndClearErrors()}>
                            Reset draft
                        </button>
                    </div>
                    <p id="draft-status">
                        {form.processing ? 'Saving' : form.wasSuccessful ? 'Saved successfully' : 'Ready'}
                    </p>
                </form>

                <Form
                    action={props.endpoints.draft}
                    method="post"
                    resetOnSuccess
                    options={{ preserveScroll: true }}
                >
                    {({ errors, processing, wasSuccessful }) => (
                        <>
                            <label htmlFor="component-title">Draft title (Form component)</label>
                            <input
                                id="component-title"
                                name="title"
                                aria-invalid={Boolean(errors.title)}
                                aria-describedby={errors.title ? 'component-error' : undefined}
                            />
                            {errors.title && <p id="component-error" role="alert">{errors.title}</p>}
                            <button id="save-component" type="submit" disabled={processing}>
                                Save with Form component
                            </button>
                            {wasSuccessful && <p id="component-success">Form component saved</p>}
                        </>
                    )}
                </Form>

                <form
                    onSubmit={(event) => {
                        event.preventDefault()
                        http.post(props.endpoints.http, {
                            onSuccess: (response) => setHttpResult(response.title),
                        })
                    }}
                >
                    <label htmlFor="http-title">Standalone HTTP title (useHttp)</label>
                    <input
                        id="http-title"
                        value={http.data.title}
                        onChange={(event) => http.setData('title', event.target.value)}
                        aria-invalid={Boolean(http.errors.title)}
                        aria-describedby={http.errors.title ? 'http-error' : undefined}
                    />
                    {http.errors.title && <p id="http-error" role="alert">{http.errors.title}</p>}
                    <button id="save-http" type="submit" disabled={http.processing}>
                        Send JSON request
                    </button>
                    <p id="http-result">{httpResult}</p>
                </form>
            </section>

            <section className="fi-inertia-workbench" aria-labelledby="inertia-boundary-heading">
                <h2 id="inertia-boundary-heading">Navigation boundary probe</h2>
                <Link href="/">Back to dashboard</Link>
            </section>
        </>
    )
}

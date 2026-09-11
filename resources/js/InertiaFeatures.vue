<script setup>
import {
    Deferred,
    Form,
    Head,
    Link,
    router,
    useForm,
    useHttp,
    usePage,
} from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()
const form = useForm('WorkbenchDraft', { title: '' })
const http = useHttp({ title: '' })
const httpResult = ref('Not submitted')

function loadContacts(reset = false) {
    router.reload({
        data: { batch: reset ? 1 : 2 },
        only: ['contacts'],
        reset: reset ? ['contacts'] : [],
    })
}

</script>

<template>
    <Head :title="`${page.props.report.title} — Inertia workbench`" />

    <section
        class="fi-inertia-workbench"
        aria-labelledby="inertia-props-heading"
    >
        <h2 id="inertia-props-heading">Inertia 3 prop experiments</h2>
        <p>
            Current content section:
            <strong id="content-section">{{ page.props.section }}</strong>
        </p>
        <Deferred data="analytics.total">
            <template #fallback><p>Loading analytics…</p></template>
            <p id="deferred-analytics">
                Deferred total: {{ page.props.analytics.total }}
            </p>
        </Deferred>
        <Deferred data="permissions">
            <template #fallback><p>Loading permissions…</p></template>
            <p id="deferred-permissions">
                Deferred once: {{ page.props.permissions.join(', ') }}
            </p>
        </Deferred>
        <p>
            Always-included request token:
            <output id="request-token">{{ page.props.requestToken }}</output>
        </p>
        <p>
            Once-only catalog token:
            <output id="catalog-token">{{ page.props.catalog.token }}</output>
        </p>
        <div class="fi-inertia-controls">
            <button
                id="load-optional"
                type="button"
                @click="
                    router.reload({
                        only: ['optionalSummary', 'analytics.audit'],
                    })
                "
            >
                Load optional props
            </button>
            <button
                id="refresh-catalog"
                type="button"
                @click="router.reload({ only: ['catalog'] })"
            >
                Refresh once-only catalog
            </button>
        </div>
        <p id="optional-summary">
            {{ page.props.optionalSummary ?? 'Optional summary not requested' }}
        </p>
        <p id="optional-audit">
            {{ page.props.analytics?.audit ?? 'Nested audit not requested' }}
        </p>

        <h3>Merging by contact ID</h3>
        <ul id="merged-contacts">
            <li v-for="contact in page.props.contacts.data" :key="contact.id">
                {{ contact.id }}: {{ contact.name }}
            </li>
        </ul>
        <div class="fi-inertia-controls">
            <button id="merge-contacts" type="button" @click="loadContacts()">
                Merge next batch
            </button>
            <button
                id="reset-contacts"
                type="button"
                @click="loadContacts(true)"
            >
                Reset contacts
            </button>
        </div>
    </section>

    <section
        class="fi-inertia-workbench"
        aria-labelledby="inertia-forms-heading"
    >
        <h2 id="inertia-forms-heading">Forms without Livewire submissions</h2>
        <p>
            Saved in this demo session:
            <strong id="saved-draft">{{ page.props.savedDraft.title }}</strong>
        </p>
        <form
            @submit.prevent="
                form.patch(page.props.endpoints.draft, { preserveScroll: true })
            "
        >
            <label for="draft-title">Remembered draft title (useForm)</label>
            <input
                id="draft-title"
                v-model="form.title"
                :aria-invalid="!!form.errors.title"
                :aria-describedby="
                    form.errors.title ? 'draft-error' : undefined
                "
            />
            <p v-if="form.errors.title" id="draft-error" role="alert">
                {{ form.errors.title }}
            </p>
            <div class="fi-inertia-controls">
                <button
                    id="save-draft"
                    type="submit"
                    :disabled="form.processing"
                >
                    Save draft via PATCH
                </button>
                <button
                    id="reset-draft"
                    type="button"
                    @click="form.resetAndClearErrors()"
                >
                    Reset draft
                </button>
            </div>
            <p id="draft-status">
                {{
                    form.processing
                        ? 'Saving'
                        : form.wasSuccessful
                          ? 'Saved successfully'
                          : 'Ready'
                }}
            </p>
        </form>

        <Form
            :action="page.props.endpoints.draft"
            method="post"
            reset-on-success
            :options="{ preserveScroll: true }"
            v-slot="{ errors, processing, wasSuccessful }"
        >
            <label for="component-title">Draft title (Form component)</label>
            <input
                id="component-title"
                name="title"
                :aria-invalid="!!errors.title"
                :aria-describedby="errors.title ? 'component-error' : undefined"
            />
            <p v-if="errors.title" id="component-error" role="alert">
                {{ errors.title }}
            </p>
            <button id="save-component" type="submit" :disabled="processing">
                Save with Form component
            </button>
            <p v-if="wasSuccessful" id="component-success">
                Form component saved
            </p>
        </Form>

        <form
            @submit.prevent="
                http.post(page.props.endpoints.http, {
                    onSuccess: (response) => {
                        httpResult = response.title
                    },
                })
            "
        >
            <label for="http-title">Standalone HTTP title (useHttp)</label>
            <input
                id="http-title"
                v-model="http.title"
                :aria-invalid="!!http.errors.title"
                :aria-describedby="http.errors.title ? 'http-error' : undefined"
            />
            <p v-if="http.errors.title" id="http-error" role="alert">
                {{ http.errors.title }}
            </p>
            <button id="save-http" type="submit" :disabled="http.processing">
                Send JSON request
            </button>
            <p id="http-result">{{ httpResult }}</p>
        </form>
    </section>

    <section
        class="fi-inertia-workbench"
        aria-labelledby="inertia-boundary-heading"
    >
        <h2 id="inertia-boundary-heading">Navigation boundary probe</h2>
        <Link href="/">Back to dashboard</Link>
    </section>
</template>

<script setup>
import { ref } from 'vue'
import { FolderPlus, Search } from '@lucide/vue'
import EmptyState from '../../../../vendor/filament/support/resources/js/vue/EmptyState.vue'
const filtered = ref(false)
const minimal = ref(false)
const created = ref(0)
const message = ref('Your workspace is ready.')

function createProject() {
    created.value++
    message.value = 'Project draft created locally.'
}

function reset() {
    filtered.value = false
    minimal.value = false
    created.value = 0
    message.value = 'Your workspace is ready.'
}
</script>

<template>
    <section class="radio-demo" data-framework="vue">
        <h2 class="fi-section-header-heading">Vue · JavaScript</h2>
        <EmptyState
            data-testid="empty-state"
            :title="filtered ? 'Filtered projects' : 'All projects'"
            :heading="filtered ? 'No matching projects' : 'No projects yet'"
            heading-tag="h3"
            :description="minimal ? '  ' : null"
            :icon-color="filtered ? 'gray' : 'primary'"
            :icon-size="filtered ? 'sm' : 'lg'"
            :compact="filtered"
            :contained="!filtered"
        >
            <template v-if="!minimal" #icon
                ><Search v-if="filtered" aria-hidden="true" /><FolderPlus
                    v-else
                    aria-hidden="true"
            /></template>
            <template v-if="!minimal" #description
                >Make room for your next <strong>great idea</strong>.</template
            >
            <template v-if="!minimal" #footer>
                <div class="radio-demo-controls">
                    <button
                        type="button"
                        data-testid="create"
                        @click="createProject"
                    >
                        Create project
                    </button>
                    <a href="#vue-projects">View workspace</a>
                </div>
            </template>
        </EmptyState>
        <div class="radio-demo-controls">
            <button
                type="button"
                data-testid="filter"
                @click="filtered = !filtered"
            >
                Toggle filtered view
            </button>
            <button
                type="button"
                data-testid="minimal"
                @click="minimal = !minimal"
            >
                Toggle details
            </button>
            <button type="button" data-testid="reset" @click="reset">
                Reset
            </button>
        </div>
        <p role="status" data-testid="result">
            {{ message }} Drafts: {{ created }}
        </p>
        <div id="vue-projects" tabindex="-1">
            Workspace ·
            {{
                created
                    ? `${created} project draft${created === 1 ? '' : 's'}`
                    : 'No saved projects'
            }}
        </div>
    </section>
</template>

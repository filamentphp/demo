<script setup>
import { ref } from 'vue'
import { Star } from '@lucide/vue'
import Badge from '../../../../vendor/filament/support/resources/js/vue/Badge.vue'
const loading = ref(false)
const disabled = ref(false)
const enhanced = ref(true)
const visible = ref(true)
const deleting = ref(false)
const removed = ref(false)
const count = ref(0)
function save() {
    loading.value = true
    count.value++
}
function finish() {
    loading.value = false
    if (deleting.value) {
        removed.value = true
        deleting.value = false
    }
}
function reset() {
    loading.value = deleting.value = removed.value = disabled.value = false
    enhanced.value = visible.value = true
    count.value = 0
}
</script>
<template>
    <div class="callout-demo" data-framework="vue">
        <h2 class="fi-section-header-heading">Vue · Project labels</h2>
        <p>
            Local-only project filters. Alt+V saves; Finish completes pending
            work.
        </p>
        <div class="radio-demo-controls" data-testid="palette">
            <Badge>New</Badge><Badge color="gray" size="xs">Archived</Badge
            ><Badge color="brand" size="sm"
                >Priority<template #icon><Star /></template></Badge
            ><Badge color="success" icon-position="after"
                >Approved<template #icon><Star /></template
            ></Badge>
        </div>
        <div
            v-if="visible"
            class="radio-demo-controls"
            data-testid="interactive-badges"
        >
            <Badge
                tag="a"
                href="#vue-projects"
                data-testid="project-link"
                :disabled="disabled"
                :tooltip="enhanced ? 'Jump to the project summary' : undefined"
                >View projects</Badge
            >
            <Badge
                tag="button"
                type="submit"
                form="vue-badge-form"
                data-testid="save-badge"
                color="brand"
                :disabled="disabled"
                :loading="loading"
                :tooltip="enhanced ? 'Save project filters (Alt+V)' : undefined"
                :key-bindings="enhanced ? ['alt+v'] : undefined"
                >{{ loading ? 'Saving filters' : 'Save filters'
                }}<template #icon><Star /></template
            ></Badge>
            <Badge
                v-if="!removed"
                data-testid="delete-badge"
                color="warning"
                :disabled="disabled"
                :on-delete="() => (deleting = true)"
                :delete-loading="deleting"
                delete-label="Remove priority filter"
                >Priority filter</Badge
            >
        </div>
        <form id="vue-badge-form" @submit.prevent="save">
            <label
                >Filter name
                <input data-testid="filter-name" value="Community garden"
            /></label>
        </form>
        <div class="icon-demo-controls">
            <button type="button" data-testid="finish" @click="finish">
                Finish</button
            ><button type="button" data-testid="reset" @click="reset">
                Reset
            </button>
        </div>
        <label
            ><input type="checkbox" data-testid="disabled" v-model="disabled" />
            Disable actions</label
        >
        <label
            ><input type="checkbox" data-testid="enhanced" v-model="enhanced" />
            Tooltips and shortcut</label
        >
        <label
            ><input type="checkbox" data-testid="visible" v-model="visible" />
            Mount interactive badges</label
        >
        <p id="vue-projects">Community garden · 12 projects</p>
        <p role="status" data-testid="result">
            {{ count }} saves requested.
            {{
                removed
                    ? 'Priority filter removed.'
                    : deleting
                      ? 'Removing priority filter…'
                      : loading
                        ? 'Saving filters…'
                        : 'Ready.'
            }}
        </p>
    </div>
</template>

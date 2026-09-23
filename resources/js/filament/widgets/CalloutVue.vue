<script setup>
import { ref } from 'vue'
import { Info, CircleCheck } from '@lucide/vue'
import Callout from '../../../../vendor/filament/support/resources/js/vue/Callout.vue'
const color = ref('gray')
const icon = ref('none')
const override = ref(false)
const details = ref(true)
const actions = ref(true)
const visible = ref(true)
const message = ref('No changes published.')
function dismiss() {
    visible.value = false
    message.value = 'Release notice dismissed locally.'
}
function reset() {
    color.value = 'gray'
    icon.value = 'none'
    override.value = false
    details.value = true
    actions.value = true
    visible.value = true
    message.value = 'No changes published.'
}
</script>
<template>
    <section class="callout-demo" data-framework="vue">
        <h2 class="fi-section-header-heading">Vue · JavaScript</h2>
        <Callout
            v-if="visible"
            data-testid="callout"
            :color="color"
            heading="Your next release"
            :icon-color="override ? 'success' : undefined"
            :icon-size="override ? 'sm' : 'lg'"
        >
            <template v-if="icon !== 'none'" #icon
                ><Info v-if="icon === 'info'" /><CircleCheck v-else
            /></template>
            <template v-if="details" #description
                >Review the <strong>release notes</strong> before
                publishing.</template
            >
            <template v-if="actions" #footer
                ><button
                    type="button"
                    data-testid="review"
                    @click="message = 'Release notes reviewed locally.'"
                >
                    Review notes</button
                ><a href="#vue-release">View release</a></template
            >
            <template v-if="actions" #controls
                ><button
                    type="button"
                    data-testid="dismiss"
                    aria-label="Dismiss release notice"
                    @click="dismiss"
                >
                    ×
                </button></template
            >
        </Callout>
        <div class="icon-demo-controls">
            <label
                >Color
                <select v-model="color" data-testid="color">
                    <option
                        v-for="value in [
                            'gray',
                            'primary',
                            'info',
                            'success',
                            'warning',
                            'danger',
                            'brand',
                        ]"
                        :key="value"
                    >
                        {{ value }}
                    </option>
                </select></label
            >
            <label
                >Icon
                <select v-model="icon" data-testid="icon">
                    <option
                        v-for="value in ['none', 'info', 'check']"
                        :key="value"
                    >
                        {{ value }}
                    </option>
                </select></label
            >
            <label
                ><input
                    v-model="override"
                    type="checkbox"
                    data-testid="override"
                />Small success icon</label
            >
            <label
                ><input
                    v-model="details"
                    type="checkbox"
                    data-testid="details"
                />Description</label
            >
            <label
                ><input
                    v-model="actions"
                    type="checkbox"
                    data-testid="actions"
                />Actions and controls</label
            >
            <button type="button" data-testid="reset" @click="reset">
                Reset
            </button>
        </div>
        <p role="status" data-testid="result">{{ message }}</p>
        <p id="vue-release" tabindex="-1">
            September release · Ready for review
        </p>
    </section>
</template>

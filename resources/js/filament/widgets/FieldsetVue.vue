<script setup>
import { ref } from 'vue'
import Fieldset from '../../../../vendor/filament/support/resources/js/vue/Fieldset.vue'

const options = ref({})
const delivery = ref(false)
const controls = {
    uncontained: 'Uncontained',
    labelHidden: 'Hide legend',
    required: 'Required mark',
    disabled: 'Disabled',
    noLabel: 'No legend',
    rtl: 'RTL',
    accent: 'Theme accent',
    richLabel: 'Rich legend',
}

function reset() {
    options.value = {}
    delivery.value = false
}
</script>

<template>
    <div class="fieldset-demo" data-framework="vue">
        <h2 class="fi-section-header-heading">Vue · JavaScript</h2>
        <Fieldset
            :label="options.noLabel ? null : 'Delivery preferences'"
            :contained="!options.uncontained"
            :label-hidden="!!options.labelHidden"
            :required="!!options.required"
            :disabled="!!options.disabled"
            :dir="options.rtl ? 'rtl' : 'ltr'"
            :class="options.accent ? 'fieldset-demo-accent' : undefined"
        >
            <template v-if="options.richLabel && !options.noLabel" #label
                ><em>Delivery preferences</em></template
            >
            <label class="fieldset-demo-option"
                ><input type="checkbox" v-model="delivery" /> Leave the parcel
                at reception</label
            >
        </Fieldset>
        <div class="fieldset-demo-controls">
            <label v-for="(label, name) in controls" :key="name"
                ><input type="checkbox" v-model="options[name]" />
                {{ label }}</label
            >
        </div>
        <button type="button" class="fi-btn fi-size-sm" @click="reset">
            Reset
        </button>
        <output>{{
            delivery ? 'Leave at reception' : 'Hand to recipient'
        }}</output>
    </div>
</template>

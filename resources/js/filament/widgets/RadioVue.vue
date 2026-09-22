<script setup>
import { ref } from 'vue'
import Radio from '../../../../vendor/filament/support/resources/js/vue/Radio.vue'
const delivery = ref('standard')
const disabled = ref(false)
const invalid = ref(false)
const required = ref(false)
const submitted = ref('Not previewed')
function submit(event) {
    submitted.value = JSON.stringify([...new FormData(event.currentTarget)])
}
function reset() {
    delivery.value = 'standard'
    submitted.value = 'Not previewed'
}
</script>

<template>
    <div class="radio-demo" data-framework="vue">
        <h2 class="fi-section-header-heading">Vue · JavaScript</h2>
        <form @submit.prevent="submit" @reset="reset">
            <fieldset>
                <legend>Delivery method</legend>
                <label v-for="value in ['standard', 'express']" :key="value">
                    <Radio
                        v-model="delivery"
                        name="delivery"
                        :value="value"
                        :disabled="disabled"
                        :valid="!invalid"
                        :required="required"
                        :aria-invalid="invalid"
                    />
                    {{
                        value === 'standard'
                            ? 'Standard · 3–5 working days'
                            : 'Express · next working day'
                    }}
                </label>
            </fieldset>
            <div class="radio-demo-controls">
                <button type="submit" class="fi-btn fi-size-sm">
                    Preview FormData
                </button>
                <button type="reset" class="fi-btn fi-size-sm">
                    Reset delivery
                </button>
                <button
                    type="button"
                    class="fi-btn fi-size-sm"
                    @click="delivery = null"
                >
                    Clear selection
                </button>
            </div>
        </form>
        <div class="radio-demo-controls">
            <label><input type="checkbox" v-model="disabled" /> Disabled</label>
            <label><input type="checkbox" v-model="invalid" /> Invalid</label>
            <label><input type="checkbox" v-model="required" /> Required</label>
        </div>
        <output>{{ delivery ?? 'No selection' }} · {{ submitted }}</output>
        <p>Preview stays in this browser. No order is placed.</p>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import Checkbox from '../../../../vendor/filament/support/resources/js/vue/Checkbox.vue'
const checked = ref(true)
const disabled = ref(false)
const invalid = ref(false)
const required = ref(false)
const submitted = ref('Not submitted')
function submit(event) {
    submitted.value = JSON.stringify([...new FormData(event.currentTarget)])
}
function reset() {
    checked.value = true
    disabled.value = false
    invalid.value = false
    required.value = false
    submitted.value = 'Not submitted'
}
</script>

<template>
    <div class="checkbox-demo" data-framework="vue">
        <h2 class="fi-section-header-heading">Vue · JavaScript</h2>
        <form @submit.prevent="submit">
            <label
                ><Checkbox
                    v-model="checked"
                    name="digest"
                    value="weekly"
                    :disabled="disabled"
                    :valid="!invalid"
                    :required="required"
                    :aria-invalid="invalid"
                />
                Send me the weekly digest</label
            >
            <button type="submit" class="fi-btn fi-size-sm">
                Preview submission
            </button>
        </form>
        <div class="checkbox-demo-controls">
            <label><input type="checkbox" v-model="disabled" /> Disabled</label>
            <label><input type="checkbox" v-model="invalid" /> Invalid</label>
            <label><input type="checkbox" v-model="required" /> Required</label>
        </div>
        <output
            >{{ checked ? 'Subscribed' : 'Not subscribed' }} ·
            {{ submitted }}</output
        >
        <button type="button" class="fi-btn fi-size-sm" @click="reset">
            Reset
        </button>
    </div>
</template>

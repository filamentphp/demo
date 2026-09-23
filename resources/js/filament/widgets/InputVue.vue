<script setup>
import { ref } from 'vue'
import Input from '../../../../vendor/filament/support/resources/js/vue/Input.vue'
import InputWrapper from '../../../../vendor/filament/support/resources/js/vue/InputWrapper.vue'
const price = ref('0')
const disabled = ref(false)
const readOnly = ref(false)
const inline = ref(false)
const result = ref('No submission yet')
const form = ref()
function submit(event) {
    result.value = JSON.stringify(
        Object.fromEntries(new FormData(event.currentTarget)),
    )
}
function reset(event) {
    event.preventDefault()
    price.value = '0'
    event.currentTarget.elements.title.value =
        event.currentTarget.elements.title.defaultValue
    event.currentTarget.elements.email.value =
        event.currentTarget.elements.email.defaultValue
    result.value = 'Reset to defaults'
}
</script>
<template>
    <div class="radio-demo" data-framework="vue">
        <h2 class="fi-section-header-heading">Vue · JavaScript</h2>
        <form ref="form" @submit.prevent="submit" @reset="reset">
            <label for="vue-title">Workshop title (uncontrolled)</label>
            <InputWrapper
                ><Input
                    id="vue-title"
                    name="title"
                    defaultValue="Botanical drawing"
                    required
            /></InputWrapper>
            <label for="vue-email">Contact email (uncontrolled)</label>
            <InputWrapper
                ><Input
                    id="vue-email"
                    name="email"
                    type="email"
                    defaultValue="ada@example.com"
                    required
            /></InputWrapper>
            <label for="vue-price">Ticket price (controlled)</label>
            <InputWrapper
                prefix="£"
                suffix="GBP"
                :inline-prefix="inline"
                :inline-suffix="inline"
                :disabled="disabled"
            >
                <Input
                    id="vue-price"
                    name="price"
                    type="number"
                    min="0"
                    step="0.5"
                    required
                    v-model="price"
                    :disabled="disabled"
                    :readonly="readOnly"
                    :inline-prefix="inline"
                    :inline-suffix="inline"
                />
            </InputWrapper>
            <div class="radio-demo-controls">
                <button type="submit">Submit locally</button
                ><button type="reset">Reset form</button>
            </div>
        </form>
        <div class="radio-demo-controls">
            <label
                ><input
                    type="checkbox"
                    data-testid="disabled"
                    v-model="disabled"
                />
                Disabled price</label
            >
            <label
                ><input
                    type="checkbox"
                    data-testid="readonly"
                    v-model="readOnly"
                />
                Read-only price</label
            >
            <label
                ><input type="checkbox" data-testid="inline" v-model="inline" />
                Inline affixes</label
            >
            <button type="button" data-testid="empty" @click="price = ''">
                Empty price
            </button>
            <button type="button" data-testid="zero" @click="price = '0'">
                Zero price
            </button>
            <button
                type="button"
                data-testid="validate"
                @click="result = form.reportValidity() ? 'Valid' : 'Invalid'"
            >
                Validate
            </button>
        </div>
        <p data-testid="state">
            Host price: {{ JSON.stringify(price) }} ({{ typeof price }})
        </p>
        <output data-testid="result">{{ result }}</output>
        <p>
            Vue emits strings here, including empty; v-model.number and
            v-model.trim opt into conversion. Updates include IME composition
            input. Reset is host-owned; native reset alone does not update
            v-model.
        </p>
    </div>
</template>

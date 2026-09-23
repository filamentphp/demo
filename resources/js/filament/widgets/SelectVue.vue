<script setup>
import { computed, ref } from 'vue'
import Select from '../../../../vendor/filament/support/resources/js/vue/Select.vue'
import InputWrapper from '../../../../vendor/filament/support/resources/js/vue/InputWrapper.vue'
const workshop = ref('drawing')
const extras = ref(['ceramics'])
const disabled = ref(false)
const inline = ref(false)
const remove = ref(false)
const result = ref('No submission yet')
const form = ref()
const options = computed(() =>
    [
        ['drawing', 'Botanical drawing'],
        ['ceramics', 'Studio ceramics'],
        ['printing', 'Printmaking'],
        ['archived', 'Watercolours (unavailable)'],
    ].filter(([value]) => !remove.value || value !== 'drawing'),
)
function submit(event) {
    result.value = JSON.stringify([...new FormData(event.currentTarget)])
}
function clear() {
    workshop.value = ''
    extras.value = []
}
function selectArchived() {
    workshop.value = 'archived'
    extras.value = ['printing', 'archived']
}
function reset(event) {
    event.preventDefault()
    workshop.value = 'drawing'
    extras.value = ['ceramics']
    event.currentTarget.elements.delivery.value = 'email'
    result.value = 'Reset to defaults'
}
</script>
<template>
    <div class="radio-demo" data-framework="vue">
        <h2 class="fi-section-header-heading">Vue · JavaScript</h2>
        <form ref="form" @submit.prevent="submit" @reset="reset">
            <label for="vue-workshop">Main workshop</label>
            <InputWrapper
                prefix="Art"
                :inline-prefix="inline"
                :disabled="disabled"
            >
                <Select
                    id="vue-workshop"
                    data-testid="workshop"
                    name="workshop"
                    required
                    v-model="workshop"
                    :disabled="disabled"
                    :inline-prefix="inline"
                >
                    <option value="">Choose a workshop</option>
                    <option
                        v-for="[value, label] in options"
                        :key="value"
                        :value="value"
                        :disabled="value === 'archived'"
                    >
                        {{ label }}
                    </option>
                </Select>
            </InputWrapper>
            <label for="vue-extras">Additional workshops (multiple)</label>
            <InputWrapper :disabled="disabled">
                <Select
                    id="vue-extras"
                    data-testid="extras"
                    name="extras"
                    multiple
                    :size="4"
                    v-model="extras"
                    :disabled="disabled"
                >
                    <option
                        v-for="[value, label] in options"
                        :key="value"
                        :value="value"
                        :disabled="value === 'archived'"
                    >
                        {{ label }}
                    </option>
                </Select>
            </InputWrapper>
            <label for="vue-delivery">Ticket delivery (uncontrolled)</label>
            <InputWrapper
                ><Select
                    id="vue-delivery"
                    name="delivery"
                    data-testid="delivery"
                    ><option value="email" selected>Email tickets</option>
                    <option value="desk">Collect at reception</option></Select
                ></InputWrapper
            >
            <div class="radio-demo-controls">
                <button type="submit" data-testid="submit">
                    Submit locally</button
                ><button type="reset" data-testid="reset">Reset form</button>
            </div>
        </form>
        <div class="radio-demo-controls">
            <label
                ><input
                    type="checkbox"
                    data-testid="disabled"
                    v-model="disabled"
                />
                Disable workshops</label
            >
            <label
                ><input type="checkbox" data-testid="inline" v-model="inline" />
                Inline prefix</label
            >
            <label
                ><input type="checkbox" data-testid="remove" v-model="remove" />
                Remove drawing option</label
            >
            <button type="button" data-testid="empty" @click="clear">
                Clear selections
            </button>
            <button
                type="button"
                data-testid="archived"
                @click="selectArchived"
            >
                Select unavailable option
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
            Selection: {{ JSON.stringify(workshop) }}; extras:
            {{ JSON.stringify(extras) }}
        </p>
        <output data-testid="result">{{ result }}</output>
        <p>
            Native Vue select binding; reset is host-owned. Removing a selected
            option preserves the model but displays no selection. Disabled
            options are omitted from submission.
        </p>
    </div>
</template>

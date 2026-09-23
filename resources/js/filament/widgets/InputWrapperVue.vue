<script setup>
import { ref } from 'vue'
import { PoundSterling } from '@lucide/vue'
import InputWrapper from '../../../../vendor/filament/support/resources/js/vue/InputWrapper.vue'
import Icon from '../../../../vendor/filament/support/resources/js/vue/Icon.vue'
const amount = ref('125')
const disabled = ref(false)
const invalid = ref(false)
const affixes = ref(true)
const icons = ref(false)
const inline = ref(false)
const actions = ref(false)
const input = ref()
function reset() {
    amount.value = '125'
    disabled.value = false
    invalid.value = false
    affixes.value = true
    icons.value = false
    inline.value = false
    actions.value = false
}
</script>
<template>
    <div class="icon-demo" data-framework="vue">
        <h2 class="fi-section-header-heading">Vue · JavaScript</h2>
        <label for="vue-price">Workshop price</label>
        <InputWrapper
            :disabled="disabled"
            :valid="!invalid"
            :prefix="affixes ? '£' : null"
            :suffix="affixes ? 'GBP' : null"
            :inline-prefix="inline"
            :inline-suffix="inline"
        >
            <template v-if="icons" #prefixIcon
                ><Icon aria-hidden="true"><PoundSterling /></Icon
            ></template>
            <template v-if="actions" #suffixActions
                ><button
                    type="button"
                    :disabled="disabled"
                    @click="amount = ''"
                >
                    Clear
                </button></template
            >
            <input
                ref="input"
                id="vue-price"
                class="fi-input"
                type="number"
                min="1"
                required
                v-model="amount"
                :disabled="disabled"
                :aria-invalid="invalid"
                :aria-describedby="invalid ? 'vue-price-error' : undefined"
            />
        </InputWrapper>
        <p v-if="invalid" id="vue-price-error" role="status">
            Enter a workshop price of at least £1.
        </p>
        <div class="icon-demo-controls">
            <label><input type="checkbox" v-model="disabled" /> Disabled</label>
            <label><input type="checkbox" v-model="invalid" /> Invalid</label>
            <label
                ><input type="checkbox" v-model="affixes" /> Text affixes</label
            >
            <label><input type="checkbox" v-model="icons" /> Icon</label>
            <label><input type="checkbox" v-model="inline" /> Inline</label>
            <label
                ><input type="checkbox" v-model="actions" /> Clear action</label
            >
        </div>
        <div class="icon-demo-controls">
            <button
                type="button"
                class="fi-btn fi-size-sm"
                @click="invalid = !input.checkValidity()"
            >
                Validate
            </button>
            <button type="button" class="fi-btn fi-size-sm" @click="reset">
                Reset
            </button>
        </div>
        <p>
            Native input value: {{ amount || 'empty' }}. Wrapper styling and
            input semantics are supplied separately.
        </p>
    </div>
</template>

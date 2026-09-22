<script setup>
import { ref } from 'vue'
import LoadingIndicator from '../../../../vendor/filament/support/resources/js/vue/LoadingIndicator.vue'

const size = ref('md')
const loading = ref(true)
const accent = ref(false)

function reset() {
    size.value = 'md'
    loading.value = true
    accent.value = false
}
</script>

<template>
    <div class="loading-indicator-demo" data-framework="vue">
        <h2 class="fi-section-header-heading">Vue · JavaScript</h2>
        <div role="status" class="loading-indicator-demo-status">
            <LoadingIndicator
                v-if="loading"
                :size="size"
                :class="accent ? 'loading-indicator-demo-accent' : undefined"
            />
            <span>{{
                loading ? 'Preparing your report…' : 'Your report is ready'
            }}</span>
        </div>
        <div class="loading-indicator-demo-controls">
            <label
                >Size
                <select v-model="size">
                    <option
                        v-for="size in ['xs', 'sm', 'md', 'lg', 'xl', '2xl']"
                        :key="size"
                    >
                        {{ size }}
                    </option>
                </select></label
            >
            <label><input type="checkbox" v-model="loading" /> Loading</label>
            <label
                ><input type="checkbox" v-model="accent" /> Theme accent</label
            >
        </div>
        <button type="button" class="fi-btn fi-size-sm" @click="reset">
            Reset
        </button>
    </div>
</template>

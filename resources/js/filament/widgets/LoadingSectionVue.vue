<script setup>
import { ref } from 'vue'
import LoadingSection from '../../../../vendor/filament/support/resources/js/vue/LoadingSection.vue'
const state = ref('loading')
const tall = ref(false)
const visible = ref(true)

function reset() {
    state.value = 'loading'
    tall.value = false
    visible.value = true
}
</script>

<template>
    <section class="fi-section">
        <div class="fi-section-content">
            <div class="actions-demo" data-framework="vue">
                <h2 class="fi-section-header-heading">
                    Vue · Project overview
                </h2>
                <p>
                    Load the latest community garden milestones. Finish manually
                    to explore success and failure.
                </p>
                <template v-if="visible">
                    <LoadingSection
                        v-if="state === 'loading'"
                        data-testid="loading-section"
                        :height="tall ? '12rem' : undefined"
                        loading-label="Loading project overview"
                    />
                    <div v-else class="fi-section" data-testid="content">
                        <div class="fi-section-content">
                            {{
                                state === 'ready'
                                    ? 'Community garden · 8 of 12 milestones complete.'
                                    : 'Project overview could not be loaded. Try again.'
                            }}
                        </div>
                    </div>
                </template>
                <div class="icon-demo-controls">
                    <button
                        type="button"
                        data-testid="load"
                        :disabled="state === 'loading'"
                        @click="state = 'loading'"
                    >
                        Load overview
                    </button>
                    <button
                        type="button"
                        data-testid="finish"
                        :disabled="state !== 'loading'"
                        @click="state = 'ready'"
                    >
                        Finish successfully
                    </button>
                    <button
                        type="button"
                        data-testid="fail"
                        :disabled="state !== 'loading'"
                        @click="state = 'error'"
                    >
                        Simulate error
                    </button>
                    <button type="button" data-testid="reset" @click="reset">
                        Reset
                    </button>
                </div>
                <label
                    ><input v-model="tall" type="checkbox" data-testid="tall" />
                    Taller placeholder</label
                >
                <label
                    ><input
                        v-model="visible"
                        type="checkbox"
                        data-testid="visible"
                    />
                    Show preview</label
                >
                <p role="status" data-testid="result">
                    {{
                        state === 'ready'
                            ? 'Project overview is ready.'
                            : state === 'error'
                              ? 'Loading failed. You can retry.'
                              : ''
                    }}
                </p>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue'
import Actions from '../../../../vendor/filament/support/resources/js/vue/Actions.vue'
const alignment = ref('start')
const fullWidth = ref(false)
const visible = ref(true)
const name = ref('Community garden')
const message = ref('No changes saved.')
function reset() {
    alignment.value = 'start'
    fullWidth.value = false
    visible.value = true
    name.value = 'Community garden'
    message.value = 'No changes saved.'
}
</script>
<template>
    <section class="actions-demo" data-framework="vue">
        <h2 class="fi-section-header-heading">Vue · JavaScript</h2>
        <form @submit.prevent="message = `Saved ${name} locally.`">
            <label
                >Project name <input v-model="name" data-testid="name"
            /></label>
            <Actions
                v-if="visible"
                :alignment="alignment"
                :full-width="fullWidth"
                data-testid="actions"
            >
                <button type="submit" data-testid="save">Save</button>
                <button
                    type="button"
                    data-testid="archive"
                    @click="message = `Archived ${name} locally.`"
                >
                    Archive
                </button>
                <a href="#vue-project">View project</a>
            </Actions>
        </form>
        <div class="icon-demo-controls">
            <label
                >Alignment
                <select v-model="alignment" data-testid="alignment">
                    <option
                        v-for="value in [
                            'start',
                            'left',
                            'center',
                            'end',
                            'right',
                            'between',
                            'justify',
                        ]"
                        :key="value"
                    >
                        {{ value }}
                    </option>
                </select></label
            >
            <label
                ><input
                    v-model="fullWidth"
                    type="checkbox"
                    data-testid="full-width"
                />Full width</label
            >
            <label
                ><input
                    v-model="visible"
                    type="checkbox"
                    data-testid="visible"
                />Show actions</label
            >
            <button type="button" data-testid="reset" @click="reset">
                Reset
            </button>
        </div>
        <p role="status" data-testid="result">{{ message }}</p>
        <p id="vue-project" tabindex="-1">Community garden · Project details</p>
    </section>
</template>

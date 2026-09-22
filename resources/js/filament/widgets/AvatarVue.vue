<script setup>
import { ref } from 'vue'
import Avatar from '../../../../vendor/filament/support/resources/js/vue/Avatar.vue'

const size = ref('lg')
const circular = ref(true)
const accent = ref(false)
const loaded = ref(false)

function reset() {
    size.value = 'md'
    circular.value = true
    accent.value = false
}
</script>

<template>
    <div class="avatar-demo" data-framework="vue">
        <h2 class="fi-section-header-heading">Vue · JavaScript</h2>
        <div class="avatar-demo-profile">
            <Avatar
                src="/images/avatar-dan.png"
                alt="Dan Harrin"
                :size="size"
                :circular="circular"
                :class="accent ? 'avatar-demo-accent' : undefined"
                @load="loaded = true"
            />
            <div>
                <strong>Dan Harrin</strong>
                <p>Filament maintainer</p>
            </div>
        </div>
        <div class="avatar-demo-controls">
            <label
                >Size
                <select v-model="size">
                    <option value="sm">Small</option>
                    <option value="md">Medium</option>
                    <option value="lg">Large</option>
                    <option value="avatar-demo-large">Custom</option>
                </select></label
            >
            <label><input type="checkbox" v-model="circular" /> Circular</label>
            <label
                ><input type="checkbox" v-model="accent" /> Theme accent</label
            >
        </div>
        <button type="button" class="fi-btn fi-size-sm" @click="reset">
            Reset to defaults
        </button>
        <output :data-loaded="loaded">{{
            loaded ? 'Image loaded · native event received' : 'Loading image'
        }}</output>
    </div>
</template>

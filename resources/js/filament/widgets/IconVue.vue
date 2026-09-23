<script setup>
import { ref } from 'vue'
import { Check, X } from '@lucide/vue'
import Icon from '../../../../vendor/filament/support/resources/js/vue/Icon.vue'

const size = ref('md')
const saved = ref(true)
const image = ref(false)
const named = ref(true)
const accent = ref(false)
function reset() {
    size.value = 'md'
    saved.value = true
    image.value = false
    named.value = true
    accent.value = false
}
</script>

<template>
    <div class="icon-demo" data-framework="vue">
        <h2 class="fi-section-header-heading">Vue · JavaScript + Lucide</h2>
        <div class="icon-demo-preview" data-testid="icon-preview">
            <Icon
                :size="size"
                :src="
                    image
                        ? `/images/icon-${saved ? 'check' : 'x'}.svg`
                        : undefined
                "
                :alt="named ? (saved ? 'Saved' : 'Not saved') : ''"
                :role="!image && named ? 'img' : undefined"
                :aria-label="
                    !image && named
                        ? saved
                            ? 'Saved'
                            : 'Not saved'
                        : undefined
                "
                :aria-hidden="named ? undefined : true"
                :class="accent ? 'icon-demo-accent' : undefined"
                :data-state="saved ? 'saved' : 'unsaved'"
                ><component :is="saved ? Check : X" aria-hidden="true"
            /></Icon>
            <span
                >{{ named ? 'Named' : 'Decorative' }}
                {{ image ? 'image' : 'SVG' }} ·
                {{ saved ? 'Saved' : 'Not saved' }}</span
            >
        </div>
        <div class="icon-demo-preview">
            <span
                v-for="size in ['xs', 'sm', 'md', 'lg', 'xl', '2xl']"
                :key="size"
                class="icon-demo-sample"
                ><Icon :size="size" aria-hidden="true"><Check /></Icon
                ><span>{{ size }}</span></span
            >
        </div>
        <div class="icon-demo-controls">
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
            <label><input type="checkbox" v-model="image" /> Image</label>
            <label
                ><input type="checkbox" v-model="named" /> Accessible
                name</label
            >
            <label
                ><input type="checkbox" v-model="accent" /> Theme accent</label
            >
        </div>
        <div class="icon-demo-controls">
            <button
                type="button"
                class="fi-btn fi-size-sm"
                @click="saved = !saved"
            >
                Toggle artwork
            </button>
            <button type="button" class="fi-btn fi-size-sm" @click="reset">
                Reset
            </button>
        </div>
    </div>
</template>

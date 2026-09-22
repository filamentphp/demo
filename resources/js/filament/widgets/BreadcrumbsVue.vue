<script setup>
import { computed, ref } from 'vue'
import Breadcrumbs from '../../../../vendor/filament/support/resources/js/vue/Breadcrumbs.vue'

const alternate = ref(false)
const rtl = ref(false)
const accent = ref(false)
const spa = ref(false)
const customSeparator = ref(false)

function navigate(event) {
    if (
        !spa.value ||
        event.button !== 0 ||
        event.metaKey ||
        event.ctrlKey ||
        event.shiftKey ||
        event.altKey
    )
        return
    event.preventDefault()
    window.Livewire.navigate(event.currentTarget.href)
}

const breadcrumbs = computed(() => [
    { label: rtl.value ? 'الرئيسية' : 'Welcome', href: '/', onClick: navigate },
    {
        label: rtl.value ? 'المنتجات' : 'Products',
        href: '/shop/products',
        onClick: navigate,
    },
    {
        label: alternate.value
            ? 'A longer selected product with several variations'
            : rtl.value
              ? 'تفاصيل المنتج'
              : 'Product details',
        ...(alternate.value
            ? { href: '/shop/products', onClick: navigate }
            : {}),
    },
])

function reset() {
    alternate.value = false
    rtl.value = false
    accent.value = false
    spa.value = false
    customSeparator.value = false
}
</script>

<template>
    <div class="breadcrumbs-demo" data-framework="vue">
        <h2 class="fi-section-header-heading">Vue · JavaScript</h2>
        <Breadcrumbs
            :breadcrumbs="breadcrumbs"
            :dir="rtl ? 'rtl' : 'ltr'"
            aria-label="Vue breadcrumbs"
            :class="accent ? 'breadcrumbs-demo-accent' : undefined"
        >
            <template v-if="customSeparator" #separator
                ><span>/</span></template
            >
            <template v-if="customSeparator" #separatorRtl
                ><span>/</span></template
            >
        </Breadcrumbs>
        <div class="breadcrumbs-demo-controls">
            <label
                ><input type="checkbox" v-model="alternate" /> Alternate
                trail</label
            >
            <label><input type="checkbox" v-model="rtl" /> RTL</label>
            <label
                ><input type="checkbox" v-model="accent" /> Theme accent</label
            >
            <label
                ><input type="checkbox" v-model="customSeparator" /> Custom
                separator</label
            >
            <label
                ><input type="checkbox" v-model="spa" /> Panel navigation</label
            >
        </div>
        <button type="button" class="fi-btn fi-size-sm" @click="reset">
            Reset
        </button>
        <output>{{
            spa
                ? 'Links use Livewire navigation'
                : 'Links use browser navigation'
        }}</output>
    </div>
</template>

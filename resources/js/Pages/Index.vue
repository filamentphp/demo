<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import FrontLayout from '@/Layouts/GuestLayout.vue';
import Slider from '@/Components/Slider.vue';
import FeaturedProducts from '@/Components/Products/FeaturedGrid.vue';
import CTA from '@/Components/CTA.vue'
const props = defineProps({
    slider:Object,
    products: Object
})
</script>

<template>
    <Head title="Welcome Home" />
    <FrontLayout>
        <Slider :mageslider='slider'/>
        <CTA />
        <FeaturedProducts :featuredProducts="products"/>
    </FrontLayout>
</template>

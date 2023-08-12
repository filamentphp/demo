<script setup>
import TheHeader from '@/Components/Header.vue';
import TheFooter from '@/Components/Footer.vue';
import { Link } from '@inertiajs/inertia-vue3';
</script>
<template>
    <TheHeader />
    <main>
        <slot />
    </main>
    <TheFooter />
</template>

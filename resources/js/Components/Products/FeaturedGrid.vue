<template>
    <section class="product-area my-12">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-6xl">
            <div class="section-title text-center mx-auto mb-12"><h2>DAILY DEALS!</h2>
                <p class=""></p>
            </div>
        </div>
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-6xl">
            <div class="grid grid-cols-4 grid-rows-1 gap-4">
                <div v-for="(Productitem, index) in featuredProducts" :key="index">
                    <Product :product="Productitem"/>
                </div>
            </div>
        </div>
    </section>
</template>
<script setup>
import Product from '@/Components/Products/Product.vue';
const props = defineProps({
    featuredProducts:Object
})
</script>

<template>
    <div class="product-gallery my-6">
        <div class="product-gallery__main">
            <div class="product-large-image-wrapper">
                <div class="single-image">
                    <img :src="activeImage" alt="" />
                </div>
            </div>
        </div>
        <div class="product-gallery__thumbnails product-small-image-wrapper flex justify-center space-x-4">
            <div :class="{'border-eastwest-500': index ===indexOfActive ? true: false }" class="thumbnail__item border-y-4 border-x-4 hover:border-eastwest-500 cursor-pointer" v-for="(imageItem, index) in Productimages" :key="index">
                <img :src="imageItem.original_url" alt="Thumbnail" @click="changeActiveImage(index)" />
            </div>
        </div>


    </div>
  </template>

  <script setup>
    import { ref } from 'vue';

    const props = defineProps({
        Productimages:Array
    })
    const indexOfActive= ref(0)
    const activeImage = ref(props.Productimages[0].original_url);
    const changeActiveImage=(index)=>{
        activeImage.value = props.Productimages[index].original_url
        indexOfActive.value = index
    }
  </script>

  <style scoped>
  /* Add any scoped styles here if needed */
  </style>

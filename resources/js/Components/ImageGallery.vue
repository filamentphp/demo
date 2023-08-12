<template>
    <div class="image-gallery">
      <ImageViewer :images="Productimages" :current-image-index="currentImage"></ImageViewer>
      <img :src="image.original_url" @click="selectImage" v-for="(image, index) in Productimages" :key="index" class="thumbnail">
      
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';
  import ImageThumbnail from './ImageThumbnail.vue';
  import ImageViewer from './ImageViewer.vue';
  const props = defineProps({
     Productimages: Array
  })
  const currentImage = ref(0);
  const changeImage=(index)=> {
        currentImage.value = index;
    }
  </script>

  <style scoped>
  .image-gallery {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
  }
  </style>

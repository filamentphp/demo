<template>
    <div class="image-viewer">
      <img :src="images[currentImageIndex]?.original_url" alt="Selected Image" class="main-image border-2">
    </div>
  </template>

  <script>
  export default {
    props: {
      images: Array,
      currentImageIndex: Number,
    },
  };
  </script>

  <style scoped>
  .image-viewer {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 400px;
  }

  .main-image {
    max-width: 100%;
    max-height: 100%;
  }
  </style>

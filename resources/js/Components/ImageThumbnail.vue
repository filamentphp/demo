<template>
    <img :src="imageSrc.original_url" @click="selectImage" class="thumbnail">
  </template>

  <script>
  export default {
    props: {
      imageSrc: String,
      index: Number,
      selected: Boolean,
      selectImage: Function,
    },
  };
  </script>

  <style scoped>
  .thumbnail {
    width: 80px;
    height: 80px;
    margin: 5px;
    cursor: pointer;
    border: 2px solid transparent;
  }

  .thumbnail.selected {
    border-color: #007bff;
  }
  </style>

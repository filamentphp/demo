<template>
    <div class="single-slider slider-height-1 ">
      <swiper :slidesPerView="1" :navigation="true"  :loop="true" :autoplay="6000" :pagination="true" :freeMode="true" :modules="modules" class="mySwiper">
        <swiper-slide
          v-for="(slide, index) in mageslider"
          :key="index"
          class="swiper-slide hero__slider"
          data-swiper-autoplay="4000"
          pauseOnMouseEnter="true"
        >
        <div class="items-center mx-auto max-w-6xl">
            <div class="container mx-auto">
                <div class="grid items-center grid-cols-1 gap-4 md:grid-cols-2">
                <div >
                    <div class="slider-content slider-animated-1">
                    <h3 class="animated">{{
                        slide.smallHeader
                    }}</h3>
                    <h1 class="animated">{{ slide.header }}</h1>
                    <div class="slider-btn btn-hover">
                        <Link class="border-2 px-6 py-1.5 text-gray-700 animated relative">{{ slide.button }}</Link>
                    </div>
                    </div>
                </div>
                <div>
                    <div class="slider-single-img slider-animated-1">
                    <img :src="'/storage/' + slide.image" :alt="slide.heading" class="animated w-full" />
                    </div>
                </div>
                </div>
            </div>
        </div>

        </swiper-slide>
      </swiper>
    </div>
  </template>

   <script>
  import { Swiper, SwiperSlide } from "swiper/vue";
  import { Autoplay, Navigation, Pagination, FreeMode } from "swiper/modules";
  import { Head, Link } from '@inertiajs/inertia-vue3';
  // Import Swiper styles
  import "swiper/css";
  import "swiper/css/navigation";
  import "swiper/css/pagination";

  export default {
    components: {
      Swiper,
      SwiperSlide,
      Head, Link
    },
    props :{
        mageslider: Object
    },
    data() {
      return {
        slides: [
          {
            badge: "latest releases",
            h2: "Samsung Galaxy A54 5G",
            p: "The Samsung Galaxy A54 5G packs 8GB of RAM while having storage options of 128GB or 256GB. It is powered by an Exynos 1380 chip with a battery of 5000mAh. It spots a 50MP main camera and 32MP selfie.",
            image: "/images/products/A1-plus.jpeg",
            alt: "Image 1",
            buttonLink: "",
            buttonText: "Buy Now",
          },
          {
            badge: "latest releases",
            h2: "Samsung Galaxy A54 5G",
            p: "The Samsung Galaxy A54 5G packs 8GB of RAM while having storage options of 128GB or 256GB. It is powered by an Exynos 1380 chip with a battery of 5000mAh. It spots a 50MP main camera and 32MP selfie.",
            image: "/images/products/A54.jpeg",
            alt: "Image 2",
            buttonLink: "",
            buttonText: "Shop Now",
          },
          {
            badge: "latest releases",
            h2: "Samsung Galaxy A54 5G",
            p: "The Samsung Galaxy A54 5G packs 8GB of RAM while having storage options of 128GB or 256GB. It is powered by an Exynos 1380 chip with a battery of 5000mAh. It spots a 50MP main camera and 32MP selfie.",
            image: "/images/products/A54.jpeg",
            alt: "Image 2",
            buttonLink: "",
            buttonText: "Shop Now",
          },
          // Add more slides as needed
        ],
      };
    },
    setup() {
      return {
        modules: [Autoplay, Navigation, Pagination, FreeMode],
      };
    },
  };
  </script>

   <style scoped>
  .swiper-container {
    width: 100%;
    height: 100%;
  }

  .swiper-slide img {
    width: 100%;
    height: 100%;
  }
  </style>

<template>
  <div class="product-wrap mb-25">
    <div class="product-img">
      <Link :href="route('view.product', product.slug)"
        ><img
          class="default-img"
          :src="'/storage/' + product.featured_image"
          :alt="product.name" /><img
          class="hover-img"
          :src="'/storage/' + product.featured_image"
          :alt="product.name"
      /></Link>
      <div class="product-action">

        <div class="pro-same-action pro-cart">
          <button @click="addToCart(product)" class="text-white">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-white w-6 h-6 inline-flex">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>

            Buy now
          </button>
        </div>
        <div class="pro-same-action pro-quickview">
          <button title="Quick View">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-white w-6 ml-2 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </button>
        </div>
      </div>
    </div>
    <div class="product-content text-center">
      <h3><Link :href="route('view.product', product.slug)">{{ product.name }}</Link></h3>
      <div class="product-rating">
        <i class="fa fa-star-o yellow"></i><i class="fa fa-star-o yellow"></i
        ><i class="fa fa-star-o yellow"></i><i class="fa fa-star-o yellow"></i
        ><i class="fa fa-star-o"></i>
      </div>
      <div class="product-price">
        <span class="font-medium">{{ currencyFormat(product.price) }} </span>
            <span class="old">{{ currencyFormat(product.old_price) }}</span>
      </div>
    </div>
  </div>
</template>
<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import { useCartStore } from '@/stores/cart';
import { useToast } from 'vue-toastification';


const cart = useCartStore();
const toast = useToast();

const addToCart = (product) => {
  if (!cart.isItemInCart(product)) {
    cart.addToCart(product);
    toast.success('Item added to cart');
  } else {
    toast.warning('Item already in cart');
  }
}
const currencyFormat = (value) => {
  return "Kes " + new Intl.NumberFormat("en-US").format(Math.round(value));
};

const props = defineProps({
    product:Object
})
</script>

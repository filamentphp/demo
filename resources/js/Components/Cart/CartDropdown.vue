<template>
    <div class="relative cart" @click="isOpen = !isOpen">
      <button class="p-2 rounded-md">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 inline-flex">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
        </svg>
        <span class="count-style bg-eastwest-500">{{ cart.totalItems }}</span>

      </button>

      <div v-if="isOpen" class="origin-top-right absolute right-0 mt-2 shopping-cart-content rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                <ul>
                    <li v-for="(item, index) in cart.items" :key="index" class="single-shopping-cart block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <div class="shopping-cart-img">
                            <a :href="route('view.product', item.slug)">
                                <img :src="'/storage/' + item.featured_image" class="img-fluid">
                            </a>
                        </div>
                        <div class="shopping-cart-title">
                            <h4>
                                <Link :href="route('view.product', item.slug)"> {{ item.name }} </Link>
                            </h4>
                            <!-- <h6>Qty: 1</h6> -->
                            <span>{{ currencyFormat(item.price) }}</span>
                        </div>
                        <div class="shopping-cart-delete">
                            <button @click="removeFromCart(index)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>

                            </button>
                        </div>
                    </li>
                </ul>
                <div v-if="cart.totalItems === 0" class="block px-4 py-2 text-sm text-gray-700">
                Your cart is empty.
                </div>
            </div>
        </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { useCartStore } from '@/stores/cart';
  import { Head, Link } from "@inertiajs/inertia-vue3";
  import { useToast } from 'vue-toastification';

  const toast = useToast();
  const cart = useCartStore();
    cart.loadFromLocalStorage();
    const isOpen = ref(false);

    const removeFromCart = (index) => {
      cart.removeFromCart(index);
      toast.error('Item removed from the cart');
    };
    const currencyFormat = (value) => {
        return "Kes " + new Intl.NumberFormat("en-US").format(Math.round(value));
    };
  </script>

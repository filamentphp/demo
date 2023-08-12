<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import CartDropdown from '@/Components/Cart/CartDropdown.vue';
</script>
<template>
  <header>
    <nav class="bg-white border px-4 lg:px-6 py-2.5 dark:bg-gray-800">
      <div class="flex flex-wrap justify-between items-center mx-auto max-w-6xl">
        <Link href="/" class="flex items-center">
          <img
            src="/images/logo.png"
            class="mr-3 h-6 sm:h-9"
            alt="Eastwest"
          />
        </Link>
        <div class="flex items-center lg:order-2">
            <CartDropdown :cartItems="cartItems" @remove="removeFromCart" />
          <a
            href="#"
            class="text-gray-800 hover:text-eastwest-500 focus:ring-4 focus:ring-eastwest-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 dark:bg-eastwest-600 dark:hover:bg-eastwest-700 focus:outline-none dark:focus:ring-eastwest-800"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>

            </a
          >
          <button
            data-collapse-toggle="mobile-menu-2"
            type="button"
            class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
            aria-controls="mobile-menu-2"
            aria-expanded="false"
          >
            <span class="sr-only">Open main menu</span>
            <svg
              class="w-6 h-6"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                clip-rule="evenodd"
              ></path>
            </svg>
            <svg
              class="hidden w-6 h-6"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"
              ></path>
            </svg>
          </button>
        </div>
        <div
          class="hidden justify-between items-center w-full lg:flex lg:w-auto lg:order-1"
          id="mobile-menu-2"
        >
          <ul
            class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0"
          >
            <li>
              <a
                href="#"
                class="block py-2 px-4 text-gray-700 rounded hover:text-eastwest-500 lg:bg-transparent  lg:p-0 dark:text-white"
                aria-current="page"
                >Home</a
              >
            </li>
            <li>
              <a
                href="#"
                class="block py-2 px-4 text-gray-700 border-b border-gray-100 hover:text-eastwest-500 lg:hover:bg-transparent lg:border-0  lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700"
                >Our Products</a
              >
            </li>
            <li>
              <a
                href="#"
                class="block py-2 px-4 text-gray-700 border-b border-gray-100 hover:text-eastwest-500 lg:hover:bg-transparent lg:border-0  lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700"
                >Our Blog</a
              >
            </li>

            <li>
              <a
                href="#"
                class="block py-2 px-4 text-gray-700 border-b border-gray-100 hover:text-eastwest-500 lg:hover:bg-transparent lg:border-0  lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700"
                >Contact Us</a
              >
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
</template>

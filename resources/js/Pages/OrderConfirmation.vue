<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import FrontLayout from "@/Layouts/GuestLayout.vue";

// $route.query

const params = new URLSearchParams(window.location.search);
const orderNumber = params.get("order_number")
console.log(orderNumber)
</script>

<template>
  <Head title="Order Confirmation" />
  <FrontLayout>
    <div class="breadcrumb-area pt-35 py-6 mb-12 bg-gray-50">
      <div
        class="flex flex-wrap justify-between items-center mx-auto max-w-6xl"
      >
        <nav aria-label="breadcrumb" class="flex">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
              <a
                href="/"
                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-eastwest-600 dark:text-gray-400 dark:hover:text-white"
              >
                <svg
                  class="w-3 h-3 mr-2.5"
                  aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
                  />
                </svg>
                Home
              </a>
            </li>
            <li aria-current="page">
              <div class="flex items-center">
                <svg
                  class="w-3 h-3 text-gray-400 mx-1"
                  aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 6 10"
                >
                  <path
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="m1 9 4-4-4-4"
                  />
                </svg>
                <Link
                  class="ml-1 text-sm font-medium text-eastwest-500 hover:text-eastwest-600 md:ml-2 dark:text-gray-400 dark:hover:text-white"
                >
                  Order Confirmation
                </Link>
              </div>
            </li>
          </ol>
        </nav>
      </div>
    </div>
    <section id="confirmed" class="my-16">
        <div class="mx-auto max-w-lg">
                <h3 class="text-3xl mb-6 text-center">Thank you for your Order</h3>

                <div class="block mx-auto">
                    <div class="mx-auto text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 text-green-400 inline-flex">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="mx-auto text-center">
                        <p class="mb-4">We have emailed you a copy of the Invoice with this Order Number, use this order number below to track your order status whenever you contact us</p>
                        <h4 class="text-xl mb-6">Your Order number: <strong>{{ orderNumber }}</strong></h4>

                        <Link href="/" class="bg-eastwest-500 px-6 mr-3 py-2.5 hover:bg-eastwest-600 text-white">Go to Products</Link>
                        <Link :href="route('contacts')" class="bg-gray-500 px-6 py-2.5 hover:bg-eastwest-600 text-white">Contact Us</Link>
                    </div>
                </div>
        </div>
    </section>
  </FrontLayout>
</template>

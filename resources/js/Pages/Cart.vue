<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import FrontLayout from "@/Layouts/GuestLayout.vue";
import { ref, computed } from "vue";
import { useCartStore } from "@/stores/cart";
import { useToast } from "vue-toastification";

const toast = useToast();
const cart = useCartStore();
const cartStore = useCartStore();
cart.loadFromLocalStorage();
const currencyFormat = (value) => {
        return "Kes " + new Intl.NumberFormat("en-US").format(Math.round(value));
};
const removeFromCart = (index) => {
      cart.removeFromCart(index);
      toast.error('Item removed from the cart');
};
const updateCart = (product) => {
  if (!cart.isItemInCart(product)) {
    cart.addToCart(product);
    toast.success('Item added to cart');
  } else {
    const existingItem = cart.items.find((item) => item.id === product.id);

    if (existingItem) {
      existingItem.quantity++;
      cart.saveToLocalStorage(); // Save the updated quantity to localStorage
      toast.success('Item quantity added to cart');
    }
  }
};
const promo = ref(false)
</script>

<template>
  <Head title="Cart" />
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
                  Cart
                </Link>
              </div>
            </li>
          </ol>
        </nav>
      </div>
    </div>
    <section class="cart__area mb-12">
      <div class="flex flex-wrap justify-between mx-auto max-w-6xl">
        <div class="container mx-auto">
          <div class="flex my-3">
            <div class="w-3/4 bg-white py-6">
              <div class="flex justify-between border-b pb-8">
                <h1 class="font-semibold text-2xl">Shopping Cart</h1>
                <h2 class="font-semibold text-2xl">{{ cart.totalItems }} Items</h2>
              </div>
              <div class="flex mt-10 mb-5">
                <h3 class="font-semibold text-gray-600 text-xs uppercase w-2/5">
                  Product Details
                </h3>
                <h3
                  class="font-semibold text-center text-gray-600 text-xs uppercase w-1/5 text-center"
                >
                  Quantity
                </h3>
                <h3
                  class="font-semibold text-center text-gray-600 text-xs uppercase w-1/5 text-center"
                >
                  Price
                </h3>
                <h3
                  class="font-semibold text-center text-gray-600 text-xs uppercase w-1/5 text-center"
                >
                  Total
                </h3>
              </div>
              <div class="flex items-center hover:bg-gray-100 px-6 py-5" v-for="(item, index) in cart.items" :key="index">
                <div class="flex w-2/5">
                  <!-- product -->
                  <div class="w-20">
                    <img
                      class="h-24"
                      :src="'/storage/' + item.featured_image"
                      alt=""
                    />
                  </div>
                  <div class="flex flex-col justify-between ml-4 flex-grow">
                    <span class="font-bold mb-4 text-sm">{{ item.name }}</span>
                    <span v-if="item.customAddons">
                        <span v-for="(custom, index) in item.customAddons" :key="index">
                            <span class="font-medium text-sm block">Custom Name: <span class="text-eastwest-500">{{ custom.CustomNameAdded.customName }}</span></span>
                                <span class="font-medium mb-3 text-sm block">Custom Number: <span class="text-eastwest-500">{{ custom.CustomNameAdded.customNumber }}</span></span>
                                <span class="font-medium text-sm inline-flex">Patch Selected: <span class="text-eastwest-500 inline-flex relative -top-4 ml-3">
                                    <span v-if="custom.patchChosen.MyPatch ==='Premier League'">
                                        <img src="/images/2023_pl_patch.png" :alt="custom.patchChosen.MyPatch" class="w-10">
                                    </span>
                                    <span v-else>
                                        <img src="/images/Mens-Champions-League-22.png" :alt="custom.patchChosen.MyPatch" class="w-10">
                                    </span>
                                </span>
                            </span>
                        </span>
                    </span>

                    <span class="text-gray-600 mb-3 text-xs">Brand: {{item.brand.name}}</span>
                    <button
                    @click="removeFromCart(index)"
                      class="font-semibold hover:text-red-600 text-left text-red-500 text-xs"
                      >Reduce quantity of the items
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-flex w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>

                      </button
                    >
                  </div>
                </div>
                <div class="flex justify-center w-1/5">
                  <svg
                    class="fill-current text-gray-600 w-3 cursor-pointer"
                    viewBox="0 0 448 512"
                    @click.prevent="removeFromCart(index)"
                  >
                    <path
                      d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"
                    />
                  </svg>

                  <input
                    class="mx-2 border text-center w-12"
                    type="text"
                    :value="item.quantity"
                  />

                  <svg
                    class="fill-current text-gray-600 w-3 cursor-pointer"
                    viewBox="0 0 448 512"
                    @click.prevent="updateCart(item)"
                  >
                    <path
                      d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"
                    />
                  </svg>
                </div>
                <span class="text-center w-1/5 font-semibold text-sm"
                  >{{ currencyFormat(item.price) }}</span
                >
                <span class="text-center w-1/5 font-semibold text-sm"
                  >{{currencyFormat(Number(item.price) * item.quantity)}}</span
                >
              </div>


              <Link
                href="/"
                class="flex font-semibold text-eastwest-600 text-sm mt-10"
              >
                <svg
                  class="fill-current mr-2 text-eastwest-600 w-4"
                  viewBox="0 0 448 512"
                >
                  <path
                    d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"
                  />
                </svg>
                Continue Shopping
              </Link>
            </div>

            <div id="summary" class="w-1/4 px-8 py-6">
              <h1 class="font-semibold text-2xl border-b pb-8">
                Order Summary
              </h1>
              <div class="flex justify-between mt-10 mb-5">
                <span class="font-semibold text-sm uppercase">Items {{ cart.totalItems }}</span>

              </div>

              <div class="py-10" v-if="promo ===true">
                <label
                  for="promo"
                  class="font-semibold inline-block mb-3 text-sm uppercase"
                  >Promo Code</label
                >
                <input
                  type="text"
                  id="promo"
                  placeholder="Enter your code"
                  class="p-2 text-sm w-full"
                />
              </div>
              <button v-if="promo ===true"
                class="bg-red-500 hover:bg-red-600 px-5 py-2 text-sm text-white uppercase"
              >
                Apply
              </button>
              <div class="border-t mt-8">
                <div
                  class="flex font-semibold justify-between py-6 text-sm uppercase"
                >
                  <span>Total cost</span>
                  <span>{{ currencyFormat(cartStore.totalAmount.toFixed(2)) }}</span>
                </div>
                <Link :href="route('checkout')"
                  class="bg-eastwest-500 font-semibold w-full hover:bg-eastwest-600 py-3 px-6 text-sm text-white uppercase block text-center"
                >
                  Checkout
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </FrontLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import FrontLayout from "@/Layouts/GuestLayout.vue";
import { Input, Textarea, Spinner } from "flowbite-vue";
import { ref } from "vue";
import { useCartStore } from "@/stores/cart";
import { useToast } from "vue-toastification";
import axios from "axios";

const Toast = useToast();
const cart = useCartStore();
const cartStore = useCartStore();
cart.loadFromLocalStorage();
const currencyFormat = (value) => {
    return "Kes " + new Intl.NumberFormat("en-US").format(Math.round(value));
};
const toastOptions = {
  position: 'top-right',
  timeout: 3000,
  closeOnClick: true,
  pauseOnFocusLoss: false,
  pauseOnHover: true,
};
const deliveryType = [
    {
        id:1,
        name: "Pick from Our Shop",
        small: "Free",
        value: "collect myself"
    },
    {
        id:2,
        name: "Deliver to my Place of Choice",
        small: "charged based on distance",
        value: "deliver to me"
    }
]
const name = ref('');
const phone = ref('');
const email = ref('');
const town = ref('');
const county = ref('');
const delivery = ref('');
const gender = ref('');
const delivery_instruction = ref('');
const deliverylocation = ref('');
const loading = ref(false);
const submitForm = async () => {
    loading.value = true;
  try {
    // Prepare form data
    let formData = {
      name: name.value,
      phone: phone.value,
      email: email.value,
      town: town.value,
      gender: gender.value,
      county: county.value,
      delivery: delivery.value,
      delivery_instruction: delivery_instruction.value,
      deliverylocation: deliverylocation.value,
      order: cart.items,
      total: cartStore.totalAmount
    };



    // Perform API submission and validation
    const response = await axios.post(route('make.order'), formData)
        .then(function (res) {
            if (res.data.status ===200) {
                Toast.success('Order created successfully!', toastOptions);
                // Reset form fields after successful submission
                name.value = '';
                phone.value = '';
                email.value = '';
                town.value = '';
                county.value = '';
                delivery_instruction.value = '';
                loading.value = false;
                const params = {
                    order_number: res.data.order_number,
                };

                const queryString = new URLSearchParams(params).toString();

                const url = `/orders/confirmation?${queryString}`;
                console.log(url)
                window.location.href = url;
                } else {
                Toast.error('Order submission failed!', toastOptions);
                }
        });
    } catch (error) {
        Toast.error('An error occurred during submission.', toastOptions);
    }
};
</script>

<template>
  <Head title="Checkout" />
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
                  Checkout
                </Link>
              </div>
            </li>
          </ol>
        </nav>
      </div>
    </div>
    <section id="checkout__page" class="mb-12">
      <div class="flex flex-wrap justify-between mx-auto max-w-6xl">
        <div class="w-3/5">
          <div class="sm:pr-6 pr-0">
            <form>
              <div class="flex justify-between space-x-6 mb-3">
                <Input
                  v-model="name"
                  placeholder="enter your first name"
                  label="First Name"
                  class="w-full"
                />
                <Input
                  v-model="phone"
                  placeholder="enter your cellphone number"
                  label="Mobile Number"
                  class="w-full"
                />
              </div>
              <div class="flex justify-between space-x-6 mb-3">
                <Input
                  v-model="email"
                  placeholder="enter your email address"
                  label="Email"
                  type="email"
                  class="w-full"
                />
                <Input
                  v-model="town"
                  placeholder="enter your town's name"
                  label="Town/City"
                  class="w-full"
                />
                <Input
                  v-model="county"
                  placeholder="enter your county's name"
                  label="County"
                  class="w-full"
                />
              </div>
              <div class="flex justify-between space-x-6 mb-4">
                <div>
                    <label for="">Gender</label>
                    <div class="flex items-center mb-4">
                        <input id="male" v-model="gender" type="radio" value="male" name="gender" class="w-4 h-4 text-eastwest-600 bg-gray-100 border-gray-300 focus:ring-eastwest-500 dark:focus:ring-eastwest-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="male" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Male</label>
                    </div>
                    <div class="flex items-center">
                        <input checked id="female" v-model="gender" type="radio" value="female" name="gender" class="w-4 h-4 text-eastwest-600 bg-gray-100 border-gray-300 focus:ring-eastwest-500 dark:focus:ring-eastwest-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="female" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Female</label>
                    </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="" class="mb-3 block">Choose Collection type</label>
                <ul class="grid w-full gap-6 md:grid-cols-2">
                    <li v-for="(item, index) in deliveryType" :key="index">
                        <input type="radio" :id="item.id" v-model="delivery" name="delivery" :value="item.value" class="hidden peer" required>
                        <label :for="item.id" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-eastwest-500 peer-checked:border-eastwest-600 peer-checked:text-eastwest-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">{{item.name}}</div>
                                <div class="w-full">{{ item.small }}</div>
                            </div>
                            <svg class="w-5 h-5 ml-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                            </svg>
                        </label>
                    </li>
                </ul>
              </div>
              <div class="mb-3" v-if="delivery === 'deliver to me'">
                <label for="deliverylocation">Enter Delivery Location below</label>
                <Input
                  v-model="deliverylocation"
                  placeholder="enter your delivery location"
                  label="Delivery Location"
                  class="w-full"
                  id="deliverylocation"
                />
              </div>
              <div class="mb-3">
                <Textarea
                  rows="4"
                  placeholder="Write your order notes like special needs for delivery..."
                  v-model="delivery_instruction"
                  label="Additional Delivery/Package Instructions"
                />
              </div>
            </form>
          </div>
        </div>
        <div class="w-2/5">
          <div class="py-4 mb-4 px-6 bg-gray-100 your-order-area">
            <h4>Your Order Summary</h4>

            <div class="your-order-product-info">
              <div class="your-order-top">
                <ul>
                  <li>Product</li>
                  <li>Total</li>
                </ul>
              </div>
              <div class="your-order-middle">
                <ul class="block">
                  <li v-for="(item, index) in cart.items" :key="index">
                    <div  class="flex justify-between w-full">
                        <span class="order-middle-left"
                      ><span class="text-xl">
                        {{ item.name }}
                      </span> Qty: {{ item.quantity }}</span
                    >
                    <span class="order-price">{{ currencyFormat(item.price) }}</span>
                    </div>
                    <span v-if="item.customAddons" class="block w-full">
                        <span v-for="(custom, index) in item.customAddons" :key="index">
                            <span class="font-medium  flex justify-between "><span>Custom Name:</span> <span class="text-eastwest-500">{{ custom.CustomNameAdded.customName }}</span></span>
                                <span class="font-medium flex justify-between mb-4"><span>Custom Number:</span> <span class="text-eastwest-500">{{ custom.CustomNameAdded.customNumber }}</span></span>
                                <span class="font-medium  flex justify-between mb-3"><span>Patch Selected:</span> <span class="text-eastwest-500 inline-flex relative -top-4 ml-3">
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

                  </li>
                </ul>
              </div>
              <div class="your-order-bottom">
                <ul>
                  <li class="your-order-shipping">Shipping</li>
                  <li><span v-if="delivery"> {{delivery}}</span> <span v-else>Not selected</span></li>
                </ul>
              </div>
              <div class="your-order-total">
                <ul>
                  <li class="order-total">Total</li>
                  <li>{{ currencyFormat(cartStore.totalAmount.toFixed(2)) }}</li>
                </ul>
              </div>
            </div>
          </div>
          <button class="bg-eastwest-500 hover:bg-eastwest-600 text-white py-2.5 w-full rounded-full" type="submit" @click.prevent="submitForm">
            <span v-if="loading" class="inline-flex items-center">Please wait ...
                <spinner class="ml-2" />
            </span>
            <span v-else>Place Order</span>
        </button>
        </div>
      </div>
    </section>
  </FrontLayout>
</template>

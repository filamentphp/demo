<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import FrontLayout from "@/Layouts/GuestLayout.vue";
import ProductGallery from "@/Components/Products/ProductGallery.vue";
import { ref, onMounted, computed } from "vue";
import { Input, Tabs, Tab, Select } from "flowbite-vue";
import axios from 'axios';
import CurveText from '@/Components/Products/Curve.vue';
import { useToast } from 'vue-toastification';
import { useCartStore } from '@/stores/cart';



const cart = useCartStore();
const toast = useToast();

const addToCart = (product) => {
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
const addCustomCart = (product) => {
    const cart = useCartStore();

    if (isCustomizing.value === true) {
        let includedPatch = null;
        let CustNames = null;
        if (ownCustom.value === 'myown' && (textHolder !== null || numberHolder !== null)) {
            CustNames = {
                customName: textHolder,
                customNumber: numberHolder,
                type: ownCustom.value,
                jerseySize: selectedSize,
                price: 200
            };

            if (patchSelected.value !== null) {
                includedPatch = {
                    MyPatch: patchSelected.value,
                    price: 200
                };
            }
        } else {
            CustNames = {
                customName: selectedPlayer.value.name,
                customNumber: selectedPlayer.value.number,
                type: ownCustom.value,
                jerseySize: selectedSize,
                price: 200
            };

            if (patchSelected.value !== null) {
                includedPatch = {
                    MyPatch: patchSelected.value,
                    price: 200
                };
            }
        }

        if (!cart.isItemInCart(product)) {
            cart.addToCart(product, includedPatch, CustNames);
            toast.success('Customized Item added to cart');
        } else {
            const existingItem = cart.items.find((item) => item.id === product.id);

            if (existingItem) {
                existingItem.quantity++;
                cart.saveToLocalStorage();
                toast.success('Customized Item quantity added to cart');
            }
        }
    }
};


const ownCustom = ref(null);
const customType=(typeString)=> {
    if (typeString==='myown') {
        ownCustom.value = typeString;
    } else {
        ownCustom.value = typeString;
    }
}
const props = defineProps({
  product: Object,
});
const currencyFormat = (value) => {
  return "Kes " + new Intl.NumberFormat("en-US").format(Math.round(value));
};
const isCustomizing = ref(false);
const startCustomization = () => {
  isCustomizing.value = true;
};

const sizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
const selectedSize = ref('M');

const personalization = {
  name: "",
  number: "",
  patchSelected: "",
};
const selectedPlayer = ref(null);
const textHolder = ref(null);
const numberHolder = ref(null);
const patchSelected = ref(null);

const patches = ref([
  {
    id: 1,
    image: "/images/2023_pl_patch.png",
    title: "Premier League",
  },
  {
    id: 2,
    image: "/images/Mens-Champions-League-22.png",
    title: "Champions League",
  },
]);

const activeTab = ref('description')

const makePlayer=()=> {
    let Payload = {
        name: textHolder.value,
        image: props.product.customizer_image
    }
    axios.post(route('make.jersey'),  Payload)
    .then((response) =>{
        console.log(response)
    })
}

</script>
<template>
  <Head :title="product.name" />
  <FrontLayout>
    <div class="breadcrumb-area pt-35 py-6 bg-gray-50">
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
                  {{ product.name }}
                </Link>
              </div>
            </li>
          </ol>
        </nav>
      </div>
    </div>
    <section class="product__area">
        <div class="flex flex-wrap justify-between mx-auto max-w-6xl" v-if="isCustomizing ===false">
            <div class="w-1/2">
                <ProductGallery :Productimages="product.media" />
            </div>
            <div class="w-1/2">
                <div class="product-details-content ml-12 py-12">
                    <h2 class="font-bold text-2xl">{{ product.name }}</h2>
                    <div class="product__details_content">
                        <div class="product-price block">
                            <span class="font-medium">{{ currencyFormat(product.price) }} </span>
                            <span class="old">{{ currencyFormat(product.old_price) }}</span>
                        </div>

                    </div>
                    <div class="pro-details-list">
                            <div v-html="product.short_desc"></div>
                        </div>

                    <div class="p-2  my-6">
                        <form class="flex items-center space-x-4">
                        <h2 class="text-lg font-semibold">Sizes:</h2>
                        <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <li v-for="size in sizes" :key="size" class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                <div class="flex items-center pl-3">
                                    <input :id="size" type="radio" v-model="selectedSize" :value="size" name="list-radio" class="w-4 h-4 text-eastwest-600 bg-gray-100 border-gray-300 focus:ring-eastwest-500 dark:focus:ring-eastwest-500 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                    <label :for="size" class="w-full py-3 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300 pr-2">{{ size }} </label>
                                </div>
                            </li>

                        </ul>

                        </form>
                    </div>
                    <hr class="pb-12">
                    <div class="product__actions space-x-8 flex justify-between">
                        <button class="bg-eastwest-500 py-2.5 hover:bg-eastwest-600 px-6 w-1/2 rounded text-white" @click.prevent="startCustomization">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-flex w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 13.5V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 9.75V10.5" />
                            </svg>

                            Personalize your Kit</button>
                        <button class="bg-gray-800 hover:bg-gray-500 py-2.5 rounded w-1/2 px-6 text-white" @click="addToCart(product)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-flex w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                            Add to Cart</button>
                    </div>
                    <div class="pro-details-meta">
                        <span>Categories :</span>
                        <ul>
                            <li v-for="(category, index) in product.categories" :key="index">
                                <a href="/shop-grid-standard">{{ category.name }}</a>
                            </li>
                        </ul>
                        </div>
                </div>
            </div>
        </div>
        <!-- is customizing invokes this -->
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-6xl" v-else>
            <div class="w-1/2">
                <div class="relative curved-text">
                    <curve-text class="absolute top-20" id="name">{{textHolder}}</curve-text>
                    <curve-text class="absolute top-40" id="number" :height="350">{{numberHolder}}</curve-text>
                    <img :src="'/storage/' + product.customizer_image" class="w-full" :alt="product.name">
                </div>

              <!-- <img id="monogramImage" data-image-id="MHR6929" data-font="pl" data-season="2023-24" data-player-type="" data-product-type="" data-kit-style="home" data-player-patches="patchesnone" :data-player-name="textHolder" data-player-number="q" class="lazyOwl" data-zoom-image="https://x1.adis.ws/v1/media/graphics/i/ArsenalDirect/MHR6929_pl?patches=patchesnone&amp;name=YOUR NAME&amp;numbers=pl-home__2023-24--q" data-smallerscreen="https://x1.adis.ws/v1/media/graphics/i/ArsenalDirect/MHR6929_pl?patches=patchesnone&amp;name=YOUR NAME&amp;numbers=pl-home__2023-24--q&amp;h=300" data-roundel="" alt="Arsenal 23/24 Home Shirt" src="https://x1.adis.ws/v1/media/graphics/i/ArsenalDirect/MHR6929_pl?patches=patchesnone&amp;name=${textHolder}&amp;numbers=pl-home__2023-24--q&amp;h=814" style="">

              <img id="monogramImage" data-image-id="MHR6929" data-font="pl" data-season="2023-24" data-player-type="" data-product-type="" data-kit-style="home" data-player-patches="patchesnone" data-player-name="YOUR NAME" data-player-number="q" class="lazyOwl" data-zoom-image="https://x1.adis.ws/v1/media/graphics/i/ArsenalDirect/MHR6929_pl?patches=patchesnone&amp;name=YOUR NAME&amp;numbers=pl-home__2023-24--q" data-smallerscreen="https://x1.adis.ws/v1/media/graphics/i/ArsenalDirect/MHR6929_pl?patches=patchesnone&amp;name=YOUR NAME&amp;numbers=pl-home__2023-24--q&amp;h=300" data-roundel="" :alt="product.name" :src="'/storage/' + product.customizer_image" style="">
                <span class="curved__text text-gray-700">{{ personalization.name }}</span> -->

            </div>
            <div class="w-1/2">
                <div class="customizer">
                    <div class="border py-4 px-6 mt-12 mb-4">
                        <h4 class="pb-3 text-2xl font-bold">Customize this product</h4>
                        <hr class="mb-4 pb-3">
                        <div class="block space-x-6">
                            <button class="py-2.5 px-6 rounded text-white bg-eastwest-500 hover:bg-eastwest-600 active:bg-eastwest-500" @click.prevent="customType('myown')">Add your Own Name and Number</button>
                            <button class="py-2.5 px-6 rounded text-white bg-slate-500 hover:bg-slate-600 active:bg-eastwest-500" @click.prevent="customType('squad')">Pick a Squad Player</button>
                        </div>
                    </div>
                    <div class="border py-6 px-6 mb-4" v-if="ownCustom==='myown'">
                        <h4 class="pb-3 text-2xl font-bold">Enter personalisation</h4>
                        <hr class="mb-4 pb-3">
                        <p class="mb-4">Add a name or number to personalise your item or to create the perfect gift!</p>
                        <div class="form__personalization bg-gray-100 rounded p-6">
                            <div class="mb-3">
                                <Input v-model="textHolder" placeholder="enter a name to be printed to the jersey" label="Jersey Name" />
                            </div>
                            <div>
                                <Input v-model="numberHolder" placeholder="enter a number to add to the jersey" label="Jersey Number" />
                            </div>
                        </div>
                    </div>
                    <div class="border py-6 px-6 mb-4" v-if="ownCustom ==='squad'">
                        <h4 class="pb-3 text-2xl font-bold">Select a Player Below</h4>
                        <hr class="mb-4 pb-3">
                        <p class="mb-4">Add a name or number to personalise your item or to create the perfect gift!</p>
                        <div class="my-2" v-if="selectedPlayer">
                            <h4 class="font-bold text-2xl">Selected Player</h4>
                            <div class="flex justify-between">
                                <span>Name: <div class="text-eastwest-500">{{ selectedPlayer.name }}</div></span>
                                <span>Number/Position: <div class="text-eastwest-500">{{ selectedPlayer.number }}</div></span>
                            </div>
                        </div>
                        <div class="form__personalization bg-gray-100 rounded p-6">
                            <div class="mb-3">
                                <label for="players" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select a Player</label>
                                <select id="players" v-model="selectedPlayer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-eastwest-500 focus:border-eastwest-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-eastwest-500 dark:focus:border-eastwest-500">
                                    <option selected>Choose a player</option>
                                    <option :value="person" v-for="person in product.team.players" :key="person.id">{{ person.name }}</option>
                                </select>

                            </div>

                        </div>
                    </div>
                    <div class="border py-6 px-6 mb-4">
                        <h4 class="pb-3 text-2xl font-bold">Select patch (optional)</h4>
                        <div class="flex space-x-4">
                            <label
                            v-for="patch in patches"
                            :key="patch.id"
                            class="flex items-center space-x-2 cursor-pointer bg-gray-200 border-gray-400 border-2 rounded p-3 px-6 focus:border-eastwest-500 hover:border-eastwest-500"
                            :id="patch.id"
                            >
                            <input
                                type="radio"
                                class="hidden"
                                name="patch"
                                :value="patch.title"
                                v-model="patchSelected"
                                :id="patch.id"
                            />
                            <div class="w-10 h-10 rounded-full border-2 border-gray-300">
                                <img :src="patch.image" alt="Option Image" class="w-full h-full rounded-full" />
                            </div>
                            <span>{{ patch.title }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="border py-6 px-6 mb-4">
                        <button @click.prevent="addCustomCart(product)" class="w-full bg-eastwest-500 py-2.5 px-6 rounded text-white animate-pulse">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="description__area">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-6xl">
            <div class="w-full">
                <tabs variant="underline" v-model="activeTab" class="py-5" id="desc__tabs"> <!-- class appends to content DIV for all tabs -->
                    <tab name="description" title="Description" id="desc__tabnames">
                        <article v-html="product.description"></article>
                    </tab>
                    <tab name="second" title="Reviews">
                    Lorem...
                    </tab>
                </tabs>
            </div>
        </div>
    </section>
  </FrontLayout>
</template>

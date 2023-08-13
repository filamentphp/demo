<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import FrontLayout from "@/Layouts/GuestLayout.vue";
import axios from 'axios';
import { useToast } from "vue-toastification";
import { ref, computed } from "vue";

const toast = useToast();

const params = new URLSearchParams(window.location.search);
const orderNumber = params.get("order_number")


const formData = ref({
  name: '',
  subject: '',
  email: '',
  message: '',
});

const submitForm = async () => {
  const recaptchaToken = await $refs.recaptcha.execute();

  if (recaptchaToken) {
    // Perform form validation here (e.g., check if fields are not empty)
    try {
      const response = await axios.post(route('inquire.order'), {
        formData: formData.value,
        recaptchaToken: recaptchaToken,
      });

      if (response.data.success) {
        formData.value = {
          name: '',
          subject: '',
          email: '',
          message: '',
        };

        toast.success('Form submitted successfully!');
      } else {
        toast.error('Error submitting the form. Please try again later.');
      }
    } catch (error) {
      console.error(error);
      toast.error('An error occurred while submitting the form.');
    }
  } else {
    toast.error('reCAPTCHA verification failed.');
  }
};
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
                  Contact Us
                </Link>
              </div>
            </li>
          </ol>
        </nav>
      </div>
    </div>
    <section id="confirmed" class="my-16">
        <div class="mx-auto max-w-3xl">
                <form @submit.prevent="submitForm" class="max-w-md mx-auto p-6 bg-white shadow-md rounded-md">
                    <div class="mb-4">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Name:</label>
                    <input type="text" id="name" v-model="formData.name" class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-eastwest-300" />
                    </div>

                    <div class="mb-4">
                    <label for="subject" class="block mb-2 text-sm font-medium text-gray-700">Subject/Order Number:</label>
                    <input type="text" id="subject" v-model="formData.subject" class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-eastwest-300" />
                    </div>

                    <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email/Mobile:</label>
                    <input type="text" id="email" v-model="formData.email" class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-eastwest-300" />
                    </div>

                    <div class="mb-4">
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-700">Message:</label>
                    <textarea id="message" v-model="formData.message" class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-eastwest-300"></textarea>
                    </div>

                    <div class="mb-4 recaptcha-container">
                    <vue-recaptcha ref="recaptcha" sitekey="YOUR_RECAPTCHA_SITE_KEY"></vue-recaptcha>
                    </div>

                    <button type="submit" class="w-full bg-eastwest-500 text-white px-4 py-2 rounded-md hover:bg-eastwest-600 focus:outline-none focus:ring focus:ring-eastwest-300">Submit</button>
                </form>

        </div>
    </section>
  </FrontLayout>
</template>

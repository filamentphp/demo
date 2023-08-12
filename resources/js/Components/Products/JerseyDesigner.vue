<template>
    <TransitionRoot
    :show="isOpen"
    as="template"
    enter="duration-300 ease-out"
    enter-from="opacity-0"
    enter-to="opacity-100"
    leave="duration-200 ease-in"
    leave-from="opacity-100"
    leave-to="opacity-0"
  >
  <Dialog @close="setIsOpen">
      <DialogPanel>
        <DialogTitle>Deactivate account</DialogTitle>
        <!-- ... -->
        <button @click="isOpen = false">Close</button>
      </DialogPanel>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import {
    TransitionRoot,
    Dialog,
    DialogPanel,
    DialogTitle,
  } from '@headlessui/vue'

</script>

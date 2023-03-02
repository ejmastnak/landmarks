<script setup>
import { InformationCircleIcon } from '@heroicons/vue/24/outline'
import { usePage } from '@inertiajs/vue3'
import {watch, ref, computed } from 'vue'

const props = defineProps({
  message: String,
})

const show = ref(true)
function hide() {
  show.value = false;
}

// Show flash message when props.flash.message changes,
// then hide the dialog after a few seconds.
watch(() => props.message, () => {
  if (props.message) {
    show.value = true;
    setTimeout(() => {
      show.value = false;
    }, 4000);
  }
});

</script>

<template>
  <Transition>
    <div
      v-show="message && show"
      @click="hide"
      class="absolute inset-x-0 mx-auto  flex items-center text-sm text-gray-800 bg-blue-50 px-4 py-3 rounded-b-md border-b-2 border-blue-400 z-50"
    >
      <InformationCircleIcon class="w-5 h-5 text-gray-600" />
      <span class="ml-1 translate-y-px">{{ message }}</span>
    </div>
  </Transition>
</template>

<style>
.v-enter-active {
  transition: opacity 0.1s ease;
}
.v-leave-active {
  transition: opacity 0.5s ease;
}
.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>

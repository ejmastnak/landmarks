<script setup>
import { InformationCircleIcon } from '@heroicons/vue/24/outline'
import {watch, ref } from 'vue'

const props = defineProps({
  message: String
})

const show = ref(true)
function hide() {
  show.value = false;
}

// Show flash message when props.flash.message changes,
// then hide the dialog after 5 seconds.
watch(() => props.message, () => {
  if (props.message) {
    show.value = true;
    setTimeout(() => {
      show.value = false;
    }, 5000);
  }
});

</script>

<template>
  <Transition>
    <div
      v-show="message && show"
      @click="hide"
      class="absolute inset-x-0 max-w-4xl w-11/12 mx-auto  flex items-center text-sm text-gray-800 bg-blue-50 px-4 py-3 -mt-6 rounded-b-md border-b-2 border-blue-400"
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

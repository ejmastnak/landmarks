<script setup>
import { Head } from '@inertiajs/vue3'
import CreateOrUpdate from './Partials/CreateOrUpdate.vue'
import PrimaryLinkButton from '@/Components/PrimaryLinkButton.vue'
import SecondaryLinkButton from '@/Components/SecondaryLinkButton.vue'

const props = defineProps({
  landmark: Object,
  showEditOption: {
    type: Boolean,
    default: false
  }
});

</script>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
export default {
  layout: AppLayout,
}
</script>

<template>
  <div class="mt-16 max-w-lg mx-auto">
    <Head :title="landmark.name" />

    <div class="p-6 border border-gray-200 shadow-sm rounded-xl">
      <h1 class="font-semibold text-xl text-gray-800 leading-tight">{{landmark.name}}</h1>

      <p class="mt-2 text-md text-gray-500">
        {{landmark.landmark_type.name}} in {{landmark.city}}, {{landmark.country.name}}
      </p>

      <div 
        v-if="landmark.comment"
        class="mt-6 mb-2"
      >
        <p class="font-semibold ml-1">Notes</p>
        <p class="p-2 border border-gray-100 rounded-md text-gray-800">
          {{landmark.comment}}
        </p>
      </div>

      <PrimaryLinkButton class="mt-6 ml-auto" :href="route('landmarks.index')">
        Back
      </PrimaryLinkButton>

      <SecondaryLinkButton
        v-if="showEditOption"
        class="mt-6 ml-2"
        :href="route('landmarks.edit', landmark.id)"
      >
        Edit
      </SecondaryLinkButton>

    </div>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import CreateOrUpdate from './Partials/CreateOrUpdate.vue'
import PrimaryLinkButton from '@/Components/PrimaryLinkButton.vue'
import SecondaryLinkButton from '@/Components/SecondaryLinkButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DeleteDialog from "@/Components/TheDeleteDialog.vue";
import { ref } from 'vue'

const props = defineProps({
  landmark: Object,
  userCanEdit: {
    type: Boolean,
    default: false
  },
  userCanDelete: {
    type: Boolean,
    default: false
  }
});

const deleteDialog = ref(null)

// function back() {
//   window.history.back();
// }

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

      <div v-if="landmark.comment" class="mt-4 mb-6" >
        <p class="font-semibold">Notes</p>
        <p class="text-gray-800 mt-0.5">
          {{landmark.comment}}
        </p>
      </div>

      <div v-if="landmark.landmark_sources.length" class="mt-6 text-sm text-gray-700">
        <p class="">Sources</p>
        <ul class="text-gray-600">
          <li v-for="source in landmark.landmark_sources" key="source.id" >
            {{source.name}}
          </li>
        </ul>
      </div>

      <div class="flex mt-6">

        <PrimaryLinkButton :href="route('landmarks.index')" >
          Back
        </PrimaryLinkButton>

        <SecondaryLinkButton
          v-if="userCanEdit"
          class="ml-auto"
          :href="route('landmarks.edit', landmark.id)"
        >
          Edit
        </SecondaryLinkButton>

        <SecondaryButton
          v-if="userCanDelete"
          class="ml-2"
          @click="deleteDialog.openToConfirmDeletion(landmark.id)"
        >
          Delete
        </SecondaryButton>

      </div>

    </div>

    <DeleteDialog ref="deleteDialog" />
  </div>
  </template>

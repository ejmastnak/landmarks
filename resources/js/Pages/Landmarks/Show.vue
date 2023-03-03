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

      <div v-if="landmark.comment" class="mt-6 mb-2" >
        <p class="font-semibold">Notes</p>
        <p class="text-gray-800 mt-1">
          {{landmark.comment}}
        </p>
      </div>

      <div class="flex">
        <PrimaryLinkButton class="mt-6" :href="route('landmarks.index')">
          Back
        </PrimaryLinkButton>

        <SecondaryLinkButton
          v-if="userCanEdit"
          class="mt-6 ml-auto"
          :href="route('landmarks.edit', landmark.id)"
        >
          Edit
        </SecondaryLinkButton>

        <SecondaryButton
          v-if="userCanDelete"
          class="mt-6 ml-2"
          @click="deleteDialog.openToConfirmDeletion(landmark.id)"
        >
          Delete
        </SecondaryButton>

      </div>

    </div>

    <DeleteDialog ref="deleteDialog" />
  </div>
  </template>

<script setup>
import { Head } from '@inertiajs/vue3'
import CreateOrUpdate from './Partials/CreateOrUpdate.vue';

const props = defineProps({
  countries: Array,
  landmarkTypes: Array
})
</script>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
export default {
  layout: AppLayout,
}
</script>

<template>
  <div class="mt-16 max-w-lg mx-auto">
    <Head title="Create landmark" />
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">Create landmark</h1>
    <CreateOrUpdate
      action="create"
      :countries="countries"
      :landmarkTypes="landmarkTypes"
    />
  </div>
</template>

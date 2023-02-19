<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import CreateOrUpdate from './Partials/CreateOrUpdate.vue'

const props = defineProps({
  landmark: Object,
  countries: Array,
  landmarkTypes: Array
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
    <Head title="Edit landmark" />
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">Edit landmark</h1>
    <CreateOrUpdate 
      :landmark="landmark"
      action="update"
      :countries="countries"
      :landmarkTypes="landmarkTypes"
    />
  </div>
</template>

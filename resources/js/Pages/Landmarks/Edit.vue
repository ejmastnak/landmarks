<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import CreateOrUpdate from './Partials/CreateOrUpdate.vue'

const props = defineProps({
  landmark: Object
});

</script>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
export default {
  layout: AppLayout,
}
</script>

<template>
  <div class="mt-16">

    <Head title="Edit landmark" />
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">Edit landmark</h1>
    <CreateOrUpdate :landmark="landmark" action="update" />

  </div>
</template>

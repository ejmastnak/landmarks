<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import CreateOrUpdate from './Partials/CreateOrUpdate.vue';
</script>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
export default {
  layout: AppLayout,
}
</script>

<template>
  <div class="mt-16">
    <Head title="Create landmark" />
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">Create landmark</h1>
    <CreateOrUpdate action="create" />
  </div>
</template>


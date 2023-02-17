<script setup>
import TheFlashMessage from '@/Components/TheFlashMessage.vue'

const props = defineProps({
  flash: Object
})

</script>

<template>
  <div class="max-w-4xl mx-auto p-6">
    <TheFlashMessage :message="flash.message" />
    <main class="mx-auto mt-12">
      <slot />
    </main>
  </div>
</template>

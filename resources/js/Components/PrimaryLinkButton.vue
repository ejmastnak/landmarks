<script setup>
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <Link
        class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-400 focus:bg-blue-400 active:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2"
    >
        <slot />
    </Link>
</template>

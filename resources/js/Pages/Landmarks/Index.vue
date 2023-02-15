<template>

  <div class="">
    <Head title="Landmarks" />

    <!-- Title and new landmark top row -->
    <div class="flex items-center">
      <h1 class="font-semibold text-2xl text-gray-900 p-1">Ottoman Landmarks in Europe</h1>
      <!-- New landmark button -->
      <Link 
        :href="route('landmarks.create')"
        as="button"
        class="ml-auto px-4 py-2 bg-blue-500 text-white hover:bg-blue-500 rounded-lg">
        New landmark
      </Link>
    </div>

    <!-- Begin Flowbite table -->
    <div class="mt-10">

      <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

        <div class="px-2 py-4 bg-white dark:bg-gray-900">
          <label for="table-search" class="sr-only">Search</label>
          <div class="relative mt-1">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
            </div>
            <input 
              type="text"
              id="table-search"
              class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
              placeholder="By name, type..."
              v-model="search"
            />
          </div>
        </div>

        <table class="table-fixed w-full text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3 bg-blue-100">
                Name
              </th>
              <th scope="col" class="px-6 py-3  w-2/12 bg-blue-200">
                Type
              </th>
              <th scope="col" class="px-6 py-3 w-2/12 bg-blue-100">
                City
              </th>
              <th scope="col" class="px-6 py-3 w-2/12 bg-blue-200">
                Country
              </th>
              <!-- For trash icon -->
              <th scope="col" class="w-10 bg-blue-100" />
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="landmark in filteredLandmarks" :key="landmark.item.id"
              class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
            >
              <th scope="row" class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                <Link
                  :href="route('landmarks.edit', landmark.item.id)"
                  class="hover:underline hover:text-blue-700">
                  {{landmark.item.name}}
                </Link>
              </th>
              <td class="px-6 py-4">
                {{landmark.item.type}}
              </td>
              <td class="px-6 py-4">
                {{landmark.item.city}}
              </td>
              <td class="px-6 py-4">
                {{landmark.item.country}}
              </td>
              <td class="">
                <DeleteIcon class="block mx-auto" :href="route('landmarks.destroy', landmark.item.id)"/>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3'
import DeleteIcon from '@/Shared/DeleteIcon.vue'
import { ref, watch, onMounted } from 'vue'
import Fuse from 'fuse.js'

const props = defineProps({
  landmarks: Array,
})

let mappedLandmarks = []
let filteredLandmarks = ref([])

onMounted(() => {
  mappedLandmarks = props.landmarks.map(landmark => ({
    item : landmark,
    refIndex: landmark.id
  }));
  filteredLandmarks.value = mappedLandmarks
})

const fuseOptions = {
  threshold: 0.4,
  minMatchCharLength: 2,
  keys: ['name']
}
const fuse = new Fuse(props.landmarks, fuseOptions)

const search = ref("")
watch(search, query => {
  if (query.trim().length <= 1) {  // only search for queries longer than 1 char
    filteredLandmarks.value = mappedLandmarks
  } else {
    filteredLandmarks.value = fuse.search(query.trim())
  }
})

</script>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
export default {
  layout: AppLayout,
}
</script>

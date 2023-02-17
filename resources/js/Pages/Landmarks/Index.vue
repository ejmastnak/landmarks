<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3'
import { TrashIcon, PlusCircleIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/outline'
import DeleteDialog from "@/Components/TheDeleteDialog.vue";
import FilterSelect from "@/Components/TheFilter.vue";
import PrimaryLinkButton from "@/Components/PrimaryLinkButton.vue";
import { ref, watch, onMounted, onBeforeUpdate, computed, reactive } from 'vue'
import Fuse from 'fuse.js'
import fuzzysort from 'fuzzysort'
import throttle from "lodash/throttle";
import debounce from "lodash/debounce";

const props = defineProps({
  landmarks: Array,
  countries: Array,
  landmarkTypes: Array
})

const allLandmarkType = props.landmarkTypes.find($l => $l.name === 'All');
const allCountry = props.countries.find($c => $c.name === 'All');
const selectedLandmarkType = ref(allLandmarkType)
const selectedCountry = ref(allCountry)

// Convert to fuzzysort format
const filteredLandmarks = ref(props.landmarks.map((landmark) => ({
  obj: landmark
})))

const search = ref("")
const fuzzysortOptions = {
  keys: ['name', 'city'],
  all: true,
  limit: 150,
  threshold: -100
}
watch(search, throttle(function (value) {
  filteredLandmarks.value = fuzzysort.go(value.trim(), props.landmarks, fuzzysortOptions)
}, 400))

// Decides if a landmark row should display in main landmarks table
// based on value of select input elements for landmark type and country.
function shouldDisplay(landmark) {
  return ((selectedLandmarkType.value.id === allLandmarkType.id) || (landmark.landmark_type_id === selectedLandmarkType.value.id)) && ((selectedCountry.value.id === allCountry.id) || (landmark.country_id === selectedCountry.value.id))
}

const numDisplayedResults = computed(() => {
  return filteredLandmarks.value.filter(result => shouldDisplay(result.obj)).length
})

// Updates filtered landmarks after landmarks was deleted on server.
// This is used to refresh landmark table to reflect a deleted landmark.
function updateFilterOnDeletion(id) {
  filteredLandmarks.value = fuzzysort.go(search.value.trim(), props.landmarks, fuzzysortOptions)
}

const deleteDialog = ref(null)

</script>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
export default {
  layout: AppLayout,
}
</script>

<template>

  <div class="mt-8">
    <Head title="Landmarks" />

    <!-- Title and new landmark top row -->
    <div class="flex items-center">
      <h1 class="font-semibold text-2xl text-gray-900 p-1">Ottoman landmarks in Europe</h1>
      <!-- New landmark button -->
      <PrimaryLinkButton 
        :href="route('landmarks.create')"
        class="flex items-center ml-auto normal-case text-xl">
        <PlusCircleIcon class="w-6 h-6" />
        <span class="ml-2 text-base">New landmark</span>
      </PrimaryLinkButton>
    </div>

    <p class="mt-2 w-2/3 text-gray-500">
      This is a list of Ottoman landmarks in Europe.
      You can use this page to view, edit, delete, or add new landmarks. 
    </p>

    <!-- Begin Flowbite table -->
    <div class="mt-8 min-h-screen relative overflow-x-auto border border-gray-100 shadow-md sm:rounded-lg">

      <!-- Search/filter components -->
      <div class="flex items-center px-2 py-4 bg-white dark:bg-gray-900">

        <!-- Input for search -->
        <div class="">
          <label for="table-search" class="ml-1 text-sm text-gray-500">
            Search by name or city
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <MagnifyingGlassIcon class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" />
            </div>

            <input 
              type="text"
              id="table-search"
              class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
              v-model="search"
            />
          </div>
        </div>

        <!-- Select menu for type -->
        <div class="ml-auto">
          <FilterSelect
            :options="landmarkTypes"
            labelText="Filter by type"
            v-model="selectedLandmarkType"
            width="w-44"
          />
        </div>

        <!-- Select menu for country -->
        <div class="ml-2">
          <FilterSelect
            :options="countries"
            labelText="Filter by country"
            v-model="selectedCountry"
          />
        </div>
      </div>

      <table class="table-fixed w-full text-base text-left text-gray-500 dark:text-gray-400">
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
            v-for="landmark in filteredLandmarks" :key="landmark.obj.id"
            v-show="shouldDisplay(landmark.obj)"
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
          >
            <th scope="row" class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
              <Link
                :href="route('landmarks.edit', landmark.obj.id)"
                class="hover:underline hover:text-blue-700">
                {{landmark.obj.name}}
              </Link>
            </th>
            <td class="px-6 py-4">
              {{landmark.obj.landmark_type.name}}
            </td>
            <td class="px-6 py-4">
              {{landmark.obj.city}}
            </td>
            <td class="px-6 py-4">
              {{landmark.obj.country.name}}
            </td>
            <td>

              <button 
                type="button" 
                @click="deleteDialog.openToConfirmDeletion(landmark.obj.id)"
                class="block mx-auto p-1 rounded-full hover:border hover:border-red-400 hover:bg-red-200"
              >
                <TrashIcon class="w-5 h-5 hover:text-red-700" />
              </button>

            </td>
          </tr>
        </tbody>
      </table>

      <p v-show="numDisplayedResults === 0" class="px-6 py-4" >
        No results found. Try a less restrictive filter or search?
      </p>

    </div>

    <!-- Delete Dialog -->
    <DeleteDialog ref="deleteDialog" @deletedALandmark="updateFilterOnDeletion" />

  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { TrashIcon, PlusCircleIcon, MagnifyingGlassIcon, ArchiveBoxArrowDownIcon, PencilSquareIcon } from '@heroicons/vue/24/outline'
import DeleteDialog from "@/Components/TheDeleteDialog.vue";
import FilterSelect from "@/Components/TheFilterSelect.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryLinkButton from "@/Components/PrimaryLinkButton.vue";
import { ref, watch, computed } from 'vue'
import fuzzysort from 'fuzzysort'
import throttle from "lodash/throttle";
import debounce from "lodash/debounce";
import { saveAs } from 'file-saver';
import { router } from '@inertiajs/vue3'

const props = defineProps({
  landmarks: Array,
  filterCountries: Array,
  filterLandmarkTypes: Array,
  userCanDelete: {
    type: Boolean,
    default: false
  },
  userCanCreate: {
    type: Boolean,
    default: false
  },
  userCanEdit: {
    type: Boolean,
    default: false
  },
})

const allLandmarkType = props.filterLandmarkTypes.find($l => $l.name === 'All');
const allCountry = props.filterCountries.find($c => $c.name === 'All');
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

// Used conditionally display a "No results found" message.
const numDisplayedResults = computed(() => {
  return filteredLandmarks.value.filter(result => shouldDisplay(result.obj)).length
})

// Updates filtered landmarks after landmarks was deleted on server.
// This is used to refresh landmark table to reflect a deleted landmark.
function updateFilterOnDeletion(id) {
  filteredLandmarks.value = fuzzysort.go(search.value.trim(), props.landmarks, fuzzysortOptions)
}
const deleteDialog = ref(null)

let exportDisabled = ref(false)
function exportToJSON() {
  exportDisabled.value = true
  axios({
    url: route('landmarks.export'),
    method: 'POST',
    data: {landmarkIDs: filteredLandmarks.value.filter(l => shouldDisplay(l.obj)).map(l => l.obj.id)},
    headers: {
      'Content-Type': 'application/json',
    }, 
    responseType: 'blob',
  }).then((response) => {
      saveAs(response.data, "landmark_export_" + new Date().toISOString().slice(0,-5).replaceAll(":", "-") + "Z.json" );
      exportDisabled.value = false
    });
}

function showLandmark(id) {
  router.get(route('landmarks.show', id), {
    preserveScroll: true
  })
}

</script>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
export default {
  layout: AppLayout,
}
</script>

<template>

  <div class="mt-8">

    <Head title="Home" />

    <!-- Title and new landmark top row -->
    <div class="flex">

      <div class="mr-2 p-1">
        <h1 class="font-semibold text-2xl text-gray-900">Ottoman landmarks in Europe</h1>
        <p class="mt-2 w-11/12 4 sm:w-2/3 text-gray-500">
          This is a list of Ottoman landmarks in Europe.
          You can use this page to view, edit, delete, or add new landmarks. 
        </p>
      </div>

      <div class="flex flex-col ml-auto">
        <!-- New landmark button -->
        <PrimaryLinkButton 
          :href="route('landmarks.create')"
          class="flex items-center py-2 sm:py-2.5 mt-1 normal-case w-full"
          :class="{'bg-blue-200': !userCanCreate}"
        >
          <PlusCircleIcon class="w-6 h-6" />
          <p class="ml-2 text-base">New <span class="hidden sm:inline">landmark</span></p>
        </PrimaryLinkButton>

        <!-- Export as JSON button -->
        <SecondaryButton 
          class="flex items-center mt-2 normal-case sm:w-max"
          :disabled="exportDisabled"
          @click="exportToJSON"
        >
          <ArchiveBoxArrowDownIcon class="w-6 h-6" />
          <p class="ml-2 text-sm">Export <span class="hidden sm:inline">landmarks</span></p>
        </SecondaryButton>
      </div>

    </div>

    <!-- Main landmark display panel -->
    <div class="mt-8 min-h-screen relative overflow-x-auto border border-gray-100 shadow-md sm:rounded-lg">

      <!-- Search/filter components -->
      <div class="flex flex-col sm:flex-row items-start px-2 py-4 bg-white">
        <!-- Input for search -->
        <div class="sm:mr-3">
          <label for="table-search" class="ml-1 text-sm text-gray-500">
            Search by name or city
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <MagnifyingGlassIcon class="w-5 h-5 text-gray-500" />
            </div>

            <input 
              type="text"
              id="table-search"
              class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 sm:w-64 md:w-80 lg:w-96 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" 
              v-model="search"
            />
          </div>
        </div>

        <!-- Select menu for type -->
        <div class="sm:ml-auto">
          <FilterSelect
            :options="filterLandmarkTypes"
            labelText="Filter by type"
            v-model="selectedLandmarkType"
            width="w-44"
          />
        </div>

        <!-- Select menu for country -->
        <div class="sm:ml-3">
          <FilterSelect
            :options="filterCountries"
            labelText="Filter by country"
            v-model="selectedCountry"
          />
        </div>
      </div>

      <table class="sm:table-fixed w-full text-sm sm:text-base text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
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
            <th v-if="userCanDelete" scope="col" class="w-14 bg-blue-100" />
          </tr>
        </thead>
        <tbody>
          <tr 
            v-for="landmark in filteredLandmarks" :key="landmark.obj.id"
            v-show="shouldDisplay(landmark.obj)"
            class="bg-white border-b"
          >
            <th scope="row" class="px-5 py-4 font-semibold text-gray-900">

              <!-- <Link -->
              <!--   :href="route('landmarks.show', landmark.obj.id)" -->
              <!--   class="hover:underline hover:text-blue-700 rounded p-1" -->
              <!--   preserve-scroll -->
              <!-- > -->
              <!--   {{landmark.obj.name}} -->
              <!-- </Link> -->

              <button
                @click="showLandmark(landmark.obj.id)"
                class="hover:underline hover:text-blue-700 rounded p-1"
              >
                {{landmark.obj.name}}
              </button>

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

            <td v-if="userCanDelete || userCanEdit">
              <div class="flex items-center px-1.5">

                <Link
                  v-if="userCanEdit"
                  class="mx-auto"
                  :href="route('landmarks.edit', landmark.obj.id)"
                >
                  <PencilSquareIcon class="w-5 h-5 hover:text-blue-600" />
                </Link>

                <button 
                  v-if="userCanDelete"
                  type="button" 
                  @click="deleteDialog.openToConfirmDeletion(landmark.obj.id)"
                  class="mx-auto"
                >
                  <TrashIcon class="w-5 h-5 hover:text-red-700" />
                </button>

              </div>
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

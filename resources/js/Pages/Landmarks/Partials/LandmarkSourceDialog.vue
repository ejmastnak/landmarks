<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Dialog, DialogPanel, DialogTitle, DialogDescription } from '@headlessui/vue'
import { PlusCircleIcon, TrashIcon } from '@heroicons/vue/24/outline'
import {ref, onMounted} from 'vue'

const props = defineProps({
  landmarkSources: Array,
  action: String
})
const emit = defineEmits(['update:landmarkSources'])

const isOpen = ref(false)

// A local "buffer" copy to edit in the scope of this component
const landmarkSourcesCopy = ref([])

// New sources are assigned negative IDs (so Controller will later recognize
// them as new sources)
var nextID = -1

function addSource() {
  landmarkSourcesCopy.value.push({id: nextID, name: ""});
  nextID -= 1;
}

function removeSource(id) {
  // Don't actually remove final item in array to preserve a visible TextInput
  if (landmarkSourcesCopy.value.length === 1) {
    landmarkSourcesCopy.value[0] = {id: nextID, name: ""};
    nextID -= 1;
  } else {
    const idx = landmarkSourcesCopy.value.map(source => source.id).indexOf(id);
    landmarkSourcesCopy.value.splice(idx, 1);
  }
}

function open() {
  isOpen.value = true;
  refreshSources();
  if (landmarkSourcesCopy.value.length === 0) {
    landmarkSourcesCopy.value.push({id: nextID, name: ""});
  }
}

function close() {
  isOpen.value = false;
  refreshSources();
}

function update() {
  emit('update:landmarkSources', landmarkSourcesCopy.value)
  close()
}

function refreshSources() {
  // I create a copy of props to avoid reactively updating props.sources when
  // updating landmarkSourcesCopy.
  landmarkSourcesCopy.value = props.landmarkSources.map(source => ({id: source.id, name: source.name}))
}

</script>

<template>
  <div class="">

    <button
      type="button"
      @click="open"
      class="mt-4 inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md text-gray-500 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
    >
      Manage sources
    </button>

    <Dialog :open="isOpen" @close="close" class="relative z-50">
      <div class="fixed inset-0 flex items-center justify-center p-4 bg-white/80 overflow-y-auto">

        <DialogPanel class="w-full flex flex-col p-8 max-w-xl min-h-[300px] shadow-sm border border-gray-200 rounded-xl bg-white">

          <DialogTitle class="text-xl text-gray-700">
            Manage sources
          </DialogTitle>

          <!-- Display list of sources -->
          <div v-if="landmarkSourcesCopy.length" class="mt-3">
            <template v-for="source in landmarkSourcesCopy" >
              <div class="flex items-center">
                <TextInput
                  type="text"
                  class="mt-1 block w-full text-sm"
                  v-model="source.name"
                />
                <button
                  type="button"
                  @click="removeSource(source.id)"
                  class="ml-1 p-1 text-gray-700 hover:text-red-700"
                >
                  <TrashIcon class="w-6 h-6" />
                </button>
              </div>
              </template>
          </div>
          <p class="mt-6 text-gray-500 text-sm" v-else >
            No sources added yet
          </p>

          <button
            type="button"
            @click="addSource"
            class="mt-4 mb-8 inline-flex items-center w-fit pl-2 pr-4 py-1 bg-white border border-gray-300 rounded-lg text-gray-500 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
          >
            <PlusCircleIcon class="w-6 h-6"/>
            <span class="ml-2 text-sm">Add source</span>
          </button>

          <div class="flex mt-auto">
            <PrimaryButton
              class="ml"
              @click="update"
            >
              Save
            </PrimaryButton>

            <SecondaryButton 
              class="ml-4"
              @click="close"
            >
              Cancel
            </SecondaryButton>
          </div>

        </DialogPanel>
      </div>
    </Dialog>

  </div>
  </template>

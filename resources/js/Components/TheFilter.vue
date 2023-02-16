<script setup>
import { ref, onMounted, computed } from 'vue'
import { ChevronDownIcon, CheckIcon } from '@heroicons/vue/24/outline'
import {
  Listbox,
  ListboxLabel,
  ListboxButton,
  ListboxOptions,
  ListboxOption,
} from '@headlessui/vue'

const props = defineProps({
  options: Array,
  labelText: String,
  modelValue: Object,
  width: {  // TailwindCSS width class for button and options
    type: String,
    default: 'w-36'
  }
})

const emit = defineEmits([
  'update:modelValue',
])

const selectedValue = computed({
  get() {
    return props.modelValue;
  },
  set(option) {
    emit('update:modelValue', option)
  }
})

</script>

<template>
  <Listbox v-model="selectedValue">
    <ListboxLabel class="ml-1 text-sm text-gray-500">
      {{labelText}}
    </ListboxLabel>
    <ListboxButton :class="width" class="flex text-left bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 px-4 py-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
      {{ modelValue.name }}
      <ChevronDownIcon class="ml-auto w-5 h-5 text-gray-500" />
    </ListboxButton>
    <ListboxOptions :class="width" class="mt-0.5 text-sm absolute bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
      <ListboxOption
        v-for="person in options"
        :key="person.id"
        :value="person"
        class="text-left cursor-pointer hover:bg-gray-100"
        v-slot="{ active, selected }"
      >
        <li :class="{
            'flex': true,
            'items-center': true,
            'px-4': true,
            'py-1.5': true,
            'bg-blue-500 text-white': active,
            'text-gray-500': !selected,
            'font-bold': selected,
          }"
        >
          {{ person.name }}
          <CheckIcon class="ml-auto w-5 h-5" v-show="selected" />
        </li>
      </ListboxOption>
    </ListboxOptions>
  </Listbox>
</template>

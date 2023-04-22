<script setup>
import { ref, computed } from 'vue'
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
  modelValue: Array,
  width: {  // TailwindCSS width class for button and options
    type: String,
    default: 'w-36'
  }
})

const emit = defineEmits([
  'update:modelValue',
])

</script>

<template>
  <Listbox
    :modelValue="modelValue"
    @update:modelValue="value => emit('update:modelValue', value)"
    multiple
  >
    <ListboxLabel class="ml-1 text-sm text-gray-500">
      {{labelText}}
    </ListboxLabel>
    <ListboxButton :class="width" class="flex text-left bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
      <span class="mr-2">
        {{ modelValue.length === 0 ? "All" : options[modelValue[0] - 1].name }}{{ modelValue.length > 1 ? "..." : ""}}
      </span>
      <ChevronDownIcon class="ml-auto w-5 h-5 text-gray-500" />
    </ListboxButton>
    <ListboxOptions :class="width" class="absolute mt-0.5 text-sm bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
      <ListboxOption
        v-for="option in options"
        :key="option.id"
        :value="option.id"
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
          {{ option.name }}
          <CheckIcon class="ml-auto w-5 h-5" v-show="selected" />
        </li>
      </ListboxOption>
    </ListboxOptions>
  </Listbox>
</template>

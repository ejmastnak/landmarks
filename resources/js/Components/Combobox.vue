<!-- https://headlessui.com/vue/combobox -->
<script setup>
import { ref, computed } from 'vue'
import { ChevronDownIcon, CheckIcon } from '@heroicons/vue/24/outline'
import {
  Combobox,
  ComboboxLabel,
  ComboboxInput,
  ComboboxButton,
  ComboboxOptions,
  ComboboxOption,
} from '@headlessui/vue'

const props = defineProps({
  options: Array,
  labelText: String,
  modelValue: Object,
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

const query = ref('')

const filteredOptions = computed(() =>
  query.value.trim() === ''
    ? props.options
    : props.options.filter((option) => {
      return option.name.toLowerCase().includes(query.value.toLowerCase())
    })
)

// Allow user to enter custom values
const customOption = computed(() => {
  return { id: -1, name: query.value };
})

</script>

<template>
  <Combobox v-model="selectedValue">

    <div>
      <ComboboxLabel class="text-sm text-gray-500">
        {{labelText}}
      </ComboboxLabel>
      <div class="relative">
        <ComboboxInput
          class="w-full border border-gray-300 rounded-md shadow-sm focus:border focus:border-blue-500"
          @change="query = $event.target.value"
          :displayValue="(option) => option.name"
        />
        <ComboboxButton tabindex="0" class="absolute right-0 px-4 rounded-md h-full focus:outline-none focus:border-2 focus:border-blue-500 active:border-0" >
          <ChevronDownIcon class="w-5 h-5"/>
        </ComboboxButton>
      </div>
    </div>

    <ComboboxOptions class="absolute z-50 mt-0.5 bg-white border border-gray-300 text-gray-900 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
      <!-- Options passed as props -->
      <ComboboxOption
        v-for="option in filteredOptions"
        :key="option.id"
        :value="option"
        class="text-left cursor-pointer"
        v-slot="{ active, selected }"
      >
        <li :class="{
          'px-4': true,
          'py-1.5': true,
          'bg-blue-500 text-white': active,
          'text-gray-500': !selected,
          'font-bold': selected,
        }"
        >
          {{ option.name }}
        </li>
      </ComboboxOption>
      <!-- A custom option entered by user -->
      <ComboboxOption
        v-if="filteredOptions.length === 0"
        :value="customOption"
        class="text-left cursor-pointer"
        v-slot="{ active, selected }"
      >
        <li :class="{
          'px-4': true,
          'py-1.5': true,
          'bg-blue-500 text-white': active,
          'text-gray-500': !selected,
          'font-bold': selected,
        }"
        >
          {{ query }}
        </li>
      </ComboboxOption>
    </ComboboxOptions>
  </Combobox>
</template>

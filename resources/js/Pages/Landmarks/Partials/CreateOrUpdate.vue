<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Combobox from '@/Components/Combobox.vue';
import SecondaryLinkButton from "@/Components/SecondaryLinkButton.vue";
import LandmarkSourceDialog from "./LandmarkSourceDialog.vue";
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  landmark: Object,
  action: String,  // "create" or "update"
  countries: Array,
  landmarkTypes: Array
});

// const allLandmarkType = props.landmarkTypes.find($l => $l.name === 'All');
// const allCountry = props.countries.find($c => $c.name === 'All');
const selectedCountry = ref(props.countries[4])
const selectedLandmarkType = ref({})

const form = useForm({
  name: props.landmark?.name,
  // Use landmarkType object that matches passed landmark's type
  landmarkType: props.landmark ? props.landmarkTypes.find($l => $l.id === props.landmark.landmark_type.id) : {},
  city: props.landmark?.city,
  // Use country object that matches passed landmark's country
  country: props.landmark ? props.countries.find($c => $c.id === props.landmark.country.id) : {},
  comment: props.landmark?.comment,
  landmarkSources: props.landmark ? props.landmark.landmark_sources.map(source => ({id: source.id, name: source.name})) : [],
});

function updateLandmarkSources(newLandmarkSources) {
  // Only use sources with non-empty names
  form.landmarkSources = newLandmarkSources.filter(source => source.name)
}

const submit = () => {
  if (props.action === "create") {
    form.post(route('landmarks.store'), { preserveScroll: true} );
  } else if (props.action === "update") {
    form.put(route('landmarks.update', props.landmark.id), { preserveScroll: true});
  }
};

function back() {
  history.back();
}

</script>

<template>
  <form @submit.prevent="submit" class="mt-4">

    <div class="w-full max-w-[22rem]">
      <InputLabel for="name" value="Name" />
      <TextInput
        id="name"
        type="text"
        class="mt-1 block w-full"
        v-model="form.name"
        required
      />
      <InputError class="mt-2" :message="form.errors.name" />
    </div>

    <div class="mt-2 w-full max-w-[14rem]">
      <Combobox labelText="Type" :options="landmarkTypes" v-model="form.landmarkType" />
      <InputError class="mt-2" :message="form.errors.landmarkType" />
      <InputError class="mt-2" :message="form.errors['landmarkType.id']" />
      <InputError class="mt-2" :message="form.errors['landmarkType.name']" />
    </div>

    <div class="flex flex-col sm:flex-row mt-4">
      <div class="max-w-[14rem]">
        <InputLabel for="city" value="City" />
        <TextInput
          id="city"
          type="text"
          class="mt-1 block w-full"
          v-model="form.city"
          required
        />
        <InputError class="mt-2" :message="form.errors.city" />
      </div>

      <div class="mt-4 sm:mt-0 sm:ml-2 max-w-[10rem]">
        <Combobox labelText="Country" :options="countries" v-model="form.country" />
        <InputError class="mt-2" :message="form.errors.country" />
        <InputError class="mt-2" :message="form.errors['country.id']" />
        <InputError class="mt-2" :message="form.errors['country.name']" />
      </div>

    </div>

    <div class="mt-4 w-full max-w-[36rem]">
      <InputLabel for="comment" value="Comments" />
      <TextAreaInput
        id="comment"
        type="text"
        class="mt-1 block w-full h-40"
        v-model="form.comment"
      />
      <InputError class="mt-2" :message="form.errors.comment" />
    </div>

    <LandmarkSourceDialog
      :action="action"
      :landmarkSources="form.landmarkSources"
      @update:landmarkSources="updateLandmarkSources"
    />

    <div class="mt-10 ml-auto">
      <PrimaryButton
        class=""
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        <span v-if="action === 'update'">Update</span>
        <span v-else>Create</span>
      </PrimaryButton>
      <SecondaryButton @click.prevent="back" class="ml-4" :href="route('landmarks.index')">
        Cancel
      </SecondaryButton>
    </div>

  </form>
  </template>

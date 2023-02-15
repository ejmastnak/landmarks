<template>

  <Head title="Create landmark" />

  <h1 class="font-semibold text-xl text-gray-800 leading-tight">Create landmark</h1>

  <form @submit.prevent="submit" class="mt-4">
    <div>
      <InputLabel for="name" value="Name" />

      <TextInput
        id="name"
        type="text"
        class="mt-1 block w-full"
        v-model="form.name"
        required
        autofocus
      />

      <InputError class="mt-2" :message="form.errors.name" />
    </div>

    <div class="mt-2">
      <InputLabel for="type" value="Type" />

      <TextInput
        id="type"
        type="text"
        class="mt-1 block"
        v-model="form.type"
        required
      />

      <InputError class="mt-2" :message="form.errors.type" />
    </div>

    <div class="flex mt-4">
      <div>
        <InputLabel for="city" value="City" />

        <TextInput
          id="city"
          type="text"
          class="mt-1 block w-48"
          v-model="form.city"
          required
        />

        <InputError class="mt-2" :message="form.errors.city" />
      </div>

      <div class="ml-2">
        <InputLabel for="city" value="Country" />

        <TextInput
          id="country"
          type="text"
          class="mt-1 block w-32"
          v-model="form.country"
          required
        />

        <InputError class="mt-2" :message="form.errors.country" />
      </div>
    </div>

    <div class="mt-4">
      <InputLabel for="comment" value="Comments" />

      <TextInput
        id="comment"
        type="text"
        class="mt-1 block w-full"
        v-model="form.comment"
      />

      <InputError class="mt-2" :message="form.errors.comment" />
    </div>

    <div class="mt-6 ml-auto">
      <PrimaryButton class="ml-2" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        Update
      </PrimaryButton>
      <SecondaryLinkButton class="ml-4" :href="route('landmarks.index')">Cancel</SecondaryLinkButton>
    </div>

  </form>

</template>

<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
  name: "",
  type: "",
  city: "",
  country: "",
  comment: ""
});

const submit = () => {
  form.post(route('landmarks.store'));
};
</script>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import SecondaryLinkButton from "@/Shared/SecondaryLinkButton.vue";
export default {
  layout: AppLayout,
}
</script>

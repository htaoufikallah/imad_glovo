<template>
  <form
      v-on:submit.prevent="handleSubmit"
      class="register shadow-md rounded px-8 pt-6 pb-8 mb-4 sm:w-1/2 md:w-1/3"
  >
    <div v-if="error" class="bg-red-500 text-white font-bold rounded-md py-2 px-4">
      {{ error }}
    </div>
    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
        Email
      </label>
      <input
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="email"
          v-model="email"
          type="email"
          placeholder="Email"
      >
    </div>
    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
        Username
      </label>
      <input
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="username"
          v-model="username"
          type="text"
          placeholder="Username"
      >
    </div>
    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
        Password
      </label>
      <input
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="password"
          v-model="password"
          type="password"
          placeholder="Password"
      >
    </div>
    <div class="mb-4">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
        Register as:
      </label>
      <select
          id="role"
          v-model="role"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
      >
        <option value="ROLE_RESTAURANT_OWNER">Restaurant Owner</option>
        <option value="ROLE_CLIENT">Client</option>
      </select>
    </div>
    <div class="flex items-center justify-between">
      <button
          class="bg-indigo-700 hover:bg-indigo-900 shadow-lg text-white font-semibold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline text-sm"
          type="submit"
          :disabled="isLoading"
          :class="{ 'bg-indigo-400': isLoading, 'hover:bg-indigo-400': isLoading }"
      >
        Register
      </button>
    </div>
  </form>
</template>

<script setup>

import { ref } from 'vue';

const email = ref('');
const username = ref('');
const password = ref('');
const role = ref('ROLE_CLIENT'); // Par défaut à ROLE_CLIENT
const error = ref('');
const isLoading = ref(false);

const handleSubmit = async () => {
  isLoading.value = true;
  error.value = '';

  const response = await fetch('/register', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      email: email.value,
      username: username.value,
      password: password.value,
      role: role.value
    })
  });

  isLoading.value = false;

  if (!response.ok) {
    const data = await response.json();
    error.value = data.error;

    return;
  }

  email.value = '';
  username.value = '';
  password.value = '';
  role.value = 'ROLE_CLIENT'; // Reset to default
  alert('Registration successful!');
}

</script>

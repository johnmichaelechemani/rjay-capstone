<template>
  <div class="w-full">
    <div>
      <h1 class="text-center font-bold text-2xl text-sky-500">Register</h1>
    </div>
    <p
      v-if="registerResponseMessage"
      class="text-green-600 px-4 shadow-sm mt-2 py-3 rounded-md bg-green-400/10"
    >
      {{ registerResponseMessage }}
    </p>
    <form class="space-y-6" @submit.prevent="signUp">
      <div>
        <label
          for="name"
          class="block text-sm font-medium leading-6 text-gray-900"
          >Store Name</label
        >
        <div class="mt-2">
          <input
            id="name"
            v-model="registerName"
            name="name"
            type="text"
            class="block w-full rounded-md px-2 border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-600 outline-none sm:text-sm sm:leading-6"
          />
        </div>
      </div>

      <div>
        <label
          for="email"
          class="block text-sm font-medium leading-6 text-gray-900"
          >Email address</label
        >
        <div class="mt-2">
          <input
            id="email"
            name="email"
            v-model="registerEmail"
            type="email"
            autocomplete="email"
            class="block w-full rounded-md px-2 border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-600 outline-none sm:text-sm sm:leading-6"
          />
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label
            for="password"
            class="block text-sm font-medium leading-6 text-gray-900"
            >Password</label
          >
        </div>
        <div class="mt-2">
          <input
            id="password"
            v-model="registerPassword"
            name="password"
            type="password"
            autocomplete="current-password"
            class="block w-full px-2 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 outline-none focus:ring-inset focus:ring-sky-600 sm:text-sm sm:leading-6"
          />
        </div>
      </div>
      <div>
        <div class="flex items-center justify-between">
          <label
            for="password"
            class="block text-sm font-medium leading-6 text-gray-900"
            >Contact Number</label
          >
        </div>
        <div class="mt-2">
          <input
            id="number"
            v-model="contactNumber"
            pattern="[0-9]{11}"
            placeholder="123-456-765-89"
            class="block w-full px-2 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 outline-none focus:ring-inset focus:ring-sky-600 sm:text-sm sm:leading-6"
          />
        </div>
      </div>

      <div>
        <button
          type="submit"
          class="flex w-full justify-center rounded-md bg-sky-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600"
        >
          Sign in
        </button>
      </div>
      <div class="flex justify-center items-center gap-2">
        Already have an account?
        <RouterLink to="/seller_Login" class="text-blue-500"
          >Sign in</RouterLink
        >
      </div>
    </form>
  </div>
</template>
<script>
import axios from "axios";
import { ref } from "vue";
export default {
  setup() {
    const registerEmail = ref("");
    const registerName = ref("");
    const registerPassword = ref("");
    const contactNumber = ref("");
    const role = ref("seller");
    const registerResponseMessage = ref("");
    const signUp = async () => {
      try {
        const url =
          "http://localhost/Ecommerce/vue-project/src/backend/auth.php?action=register";
        const res = await axios.post(
          url,
          {
            name: registerName.value,
            email: registerEmail.value,
            password: registerPassword.value,
            contact_number: contactNumber.value,
            role: role.value,
          },
          { headers: { "Content-Type": "application/json" } }
        );
        registerResponseMessage.value = res.data.message;
      } catch (res) {
        console.log(res.data.success);
      }
      registerName.value = "";
      registerEmail.value = "";
      registerPassword.value = "";
      contactNumber.value = "";
      role.value = "";
    };
    return {
      registerName,
      registerEmail,
      registerPassword,
      contactNumber,
      signUp,
      registerResponseMessage,
    };
  },
};
</script>

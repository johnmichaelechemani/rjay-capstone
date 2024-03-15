<template>
  <div class="w-full">
    <div>
      <h1 class="text-center font-bold text-2xl text-sky-500">Login</h1>
    </div>
    <form class="space-y-6" @submit.prevent="signIn">
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
            required
            v-model="loginEmail"
            type="email"
            autocomplete="email"
            class="block w-full rounded-md bg-slate-400/20 border border-blue-500/25 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-600 outline-none sm:text-sm sm:leading-6"
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
            name="password"
            required
            v-model="loginPassword"
            type="password"
            autocomplete="current-password"
            class="block w-full px-2 rounded-md py-1.5 text-gray-900 bg-slate-400/20 border border-blue-500/25 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 outline-none focus:ring-inset focus:ring-sky-600 sm:text-sm sm:leading-6"
            :class="{ 'border-red-500': passwordError }"
          />
        </div>

        <span v-if="passwordError" class="text-red-500 text-sm py-1">{{
          passwordError
        }}</span>
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
        Don't have an account?
        <RouterLink to="/seller_register" class="text-blue-500"
          >Sign up</RouterLink
        >
      </div>
    </form>
  </div>
</template>
<script>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
export default {
  setup() {
    const loginEmail = ref("");
    const loginPassword = ref("");
    const router = useRouter();
    const passwordError = ref("");
    let name = ref("");

    const signIn = async () => {
      try {
        const url =
          "http://localhost/Ecommerce/vue-project/src/backend/seller/sellerAuth.php?action=login";
        const res = await axios.post(
          url,
          {
            email: loginEmail.value,
            password: loginPassword.value,
          },
          { headers: { "Content-Type": "application/json" } }
        );
        if (res.data.error) {
          passwordError.value = res.data.message;
        }
        name.value = res.data.store;
        localStorage.setItem("seller", JSON.stringify(name.value));

        const role = res.data.store_role;
        // console.log(res.data);
        if (role === "seller") {
          router.push("/seller_dashboard");
        } else if (role === "admin") {
          router.push("/admin_dashboard");
        }
      } catch {}
    };
    return {
      loginEmail,
      loginPassword,
      signIn,
      passwordError,
    };
  },
};
</script>

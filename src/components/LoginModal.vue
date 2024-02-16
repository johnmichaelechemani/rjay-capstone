<template>
  <div v-if="isVisible">
    <div v-if="showLogin">
      <div
        class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur overflow-y-auto h-full w-full z-50"
      >
        <div
          class="bg-slate-300 relative top-20 mx-auto border border-slate-900/20 shadow-lg px-3 py-2 w-96 rounded-lg"
        >
          <div class="flex justify-end">
            <div
              class="bg-slate-600/20 rounded-full text-red-500 shadow p-2"
              @click="close"
            >
              <Icon icon="iconamoon:close-bold" />
            </div>
          </div>
          <div class="text-white px-3 py-2 rounded-md mt-3">
            <h1 class="font-bold text-sky-900 text-2xl text-center">Login</h1>
          </div>
          <form @submit.prevent="signIn">
            <div class="p-2 rounded-md shadow-sm mb-2">
              <div class="gap-2 mt-2">
                <p class="font-semibold">
                  Email <span class="text-red-500">*</span>
                </p>
                <input
                  type="email"
                  id="email"
                  v-model="loginEmail"
                  required
                  class="w-full p-2 rounded-md my-1 bg-gray-100"
                />
              </div>
              <div class="gap-2">
                <p class="font-semibold">
                  Password <span class="text-red-500">*</span>
                </p>
                <input
                  type="password"
                  id="password"
                  v-model="loginPassword"
                  required
                  class="w-full p-2 rounded-md my-1 bg-gray-100"
                />
              </div>
              <div class="my-5">
                <button
                  type="submit"
                  class="bg-sky-900 w-full font-semibold text-lg text-white px-5 py-2 rounded-md"
                >
                  Sign In
                </button>
                <div class="flex justify-center gap-2 py-2">
                  <p>Don't have account?</p>
                  <span
                    class="text-blue-500 hover:text-blue-700"
                    @click="showRegisterModal"
                    >Sign up
                  </span>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div v-if="showRegister">
      <div
        class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur overflow-y-auto h-full w-full z-50"
      >
        <div
          class="bg-slate-300 relative top-20 mx-auto border border-slate-900/20 shadow-lg px-3 py-2 w-96 rounded-lg"
        >
          <div class="flex justify-end">
            <div
              class="bg-slate-600/20 rounded-full text-red-500 shadow p-2"
              @click="close"
            >
              <Icon icon="iconamoon:close-bold" />
            </div>
          </div>
          <div class="text-white px-3 py-2 rounded-md mt-3">
            <h1 class="font-bold text-sky-900 text-2xl text-center">
              Register
            </h1>
            <p
              v-if="registerResponseMessage"
              class="text-green-600 px-4 shadow-sm mt-2 py-3 rounded-md bg-green-400/10"
            >
              {{ registerResponseMessage }}
            </p>
          </div>
          <form @submit.prevent="signUp">
            <div class="p-2 rounded-md shadow-sm mb-2">
              <div class="gap-2 mt-2">
                <p class="font-semibold">
                  Name <span class="text-red-500">*</span>
                </p>
                <input
                  type="name"
                  id="name"
                  v-model="registerName"
                  required
                  class="w-full p-2 rounded-md my-1 bg-gray-100"
                />
              </div>
              <div class="gap-2 mt-2">
                <p class="font-semibold">
                  Email <span class="text-red-500">*</span>
                </p>
                <input
                  type="email"
                  id="email"
                  v-model="registerEmail"
                  required
                  class="w-full p-2 rounded-md my-1 bg-gray-100"
                />
              </div>
              <div class="gap-2">
                <p class="font-semibold">
                  Password <span class="text-red-500">*</span>
                </p>
                <input
                  type="password"
                  v-model="registerPassword"
                  id="password"
                  required
                  class="w-full p-2 rounded-md my-1 bg-gray-100"
                />
              </div>
              <div class="gap-2">
                <p class="font-semibold">
                  Contact Number <span class="text-red-500">*</span>
                </p>
                <input
                  type="tel"
                  id="number"
                  v-model="contactNumber"
                  pattern="[0-9]{11}"
                  placeholder="123-456-765-89"
                  required
                  class="w-full p-2 rounded-md my-1 bg-gray-100"
                />
              </div>
              <div class="my-5">
                <button
                  type="submit"
                  class="bg-sky-900 w-full font-semibold text-lg text-white px-5 py-2 rounded-md"
                >
                  Sign In
                </button>
                <div class="flex justify-center gap-2 py-2">
                  <p>Already have account?</p>
                  <span
                    class="text-blue-500 hover:text-blue-700"
                    @click="showLoginModal"
                    >Sign In
                  </span>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { Icon } from "@iconify/vue";
import { onMounted, ref } from "vue";
import RegisterModal from "./RegisterModal.vue";
import axios from "axios";
export default {
  components: {
    Icon,
    RegisterModal,
  },
  props: {
    isVisible: {
      type: Boolean,
      required: true,
    },
  },
  emits: ["update:isVisible", "login-completed"],

  data() {
    return {
      showRegister: false,
      showLogin: true,
    };
  },
  methods: {
    showRegisterModal() {
      this.showRegister = true;
      this.showLogin = false;
    },
    showLoginModal() {
      this.showRegister = false;
      this.showLogin = true;
    },

    close() {
      this.$emit("update:isVisible", false);
    },
  },
  setup() {
    const loginEmail = ref("");
    const loginPassword = ref("");
    const signIn = () => {
      console.log(loginEmail.value);
      console.log(loginPassword.value);
    };
    const registerEmail = ref("");
    const registerName = ref("");
    const registerPassword = ref("");
    const contactNumber = ref("");
    const role = ref("customer");
    const registerResponseMessage = ref("");

    const signUp = async () => {
      try {
        const url =
          "http://localhost/Ecommerce/vue-project/src/backend/auth.php";
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
      } catch {
        console.log("error");
      }
    };

    return {
      loginEmail,
      loginPassword,
      signIn,

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

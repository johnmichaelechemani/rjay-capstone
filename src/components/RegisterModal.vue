<template>
  <div v-if="isVisible">
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
          <h1 class="font-bold text-sky-900 text-2xl text-center">Register</h1>
        </div>
        <div class="p-2 rounded-md shadow-sm mb-2">
          <div class="gap-2 mt-2">
            <p class="font-semibold">
              Email <span class="text-red-500">*</span>
            </p>
            <input
              type="email"
              id="email"
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
              required
              class="w-full p-2 rounded-md my-1 bg-gray-100"
            />
          </div>
          <div class="my-5">
            <button
              class="bg-sky-900 w-full font-semibold text-lg text-white px-5 py-2 rounded-md"
            >
              Sign Up
            </button>
            <div class="flex justify-center gap-2 py-2">
              <p>Already have account?</p>
              <span class="text-blue-500 hover:text-blue-700">Sign In</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { Icon } from "@iconify/vue";
import { onMounted, ref } from "vue";
import axios from "axios";
export default {
  components: {
    Icon,
  },
  props: {
    isVisible: {
      type: Boolean,
      required: true,
    },
  },
  emits: ["update:isVisible", "register-completed"],

  setup(prps, { emit }) {
    const close = () => {
      emit("update:isVisible", false);
    };
    return { close };
  },
};
</script>

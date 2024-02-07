<template>
  <div v-if="showLogin" class="flex justify-center items-center">
    <div
      class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur overflow-y-auto h-full w-full z-50"
    >
      <div
        class="bg-slate-300 border border-slate-900/20 shadow-lg px-3 py-2 w-96 rounded-lg"
      >
        <div class="flex justify-end">
          <div
            @click="closeLogin"
            class="bg-slate-600/20 rounded-full text-red-500 shadow p-2"
          >
            <Icon icon="iconamoon:close-bold" />
          </div>
        </div>
        <div>
          <h1 class="font-semibold text-lg">Login</h1>
        </div>
        <div class="p-2 bg-slate-500/10 rounded-md shadow-sm">
          <p>Intel Core i10 16th gen</p>
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
  setup() {
    const showLogin = ref(false);
    const closeLogin = () => {
      showLogin.value = false;
    };
    const Handlesignin = () => {
      showLogin.value = true;
      console.log("sign in");
    };
    return {
      Handlesignin,
      showLogin,
      closeLogin,
    };
  },
};
</script>

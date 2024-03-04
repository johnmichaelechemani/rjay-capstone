<template>
  <div class="bg-sky-900 text-white py-5 flex justify-start items-center gap-3">
    <h1 class="text-lg pl-5">Admin</h1>
    <span
      class="text-xl font-semibold px-4 py-1 capitalize bg-blue-500/50 rounded-full"
      >{{ userLogin.store_name }}</span
    >
  </div>
  <div
    class="flex sm:hidden py-2 pl-2 sm:pl-8 bg-slate-200 gap-2 sm:text-sm text-xs"
  >
    <button
      @click="handleSidebarCategory"
      class="bg-slate-700/10 py-2 px-2 rounded-full font-semibold shadow"
    >
      <Icon icon="gg:menu-round" class="text-xl text-sky-800" />
    </button>
  </div>

  <div :class="showCategory ? 'flex relative' : ''">
    <div
      v-if="showCategory"
      class="text-base min-h-full w-72 shadow absolute z-10 sm:relative font-medium bg-gray-50 border border-r-slate-700/10"
    >
      <div class="h-screen">
        <p class="px-3 pt-5 text-sky-800">Dashboard</p>
        <hr class="my-2" />

        <RouterLink to="/admin_dashboard_customers">
          <div
            class="w-full bg-slate-500/10 py-3 p-2 mt-5"
            :class="{
              ' text-sky-700': $route.name === 'dashboard_customers',
              'text-sky-900': $route.name !== 'dashboard_customers',
              ' font-semibold text-base': true,
            }"
          >
            User Management
          </div>
        </RouterLink>
      </div>
    </div>

    <div class="bg-white cursor-pointer">
      <div class="w-full px-4 sm:pt-10">
        <div>
          <!--  -->
          <router-view> </router-view>
          <!--  -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
import { Icon } from "@iconify/vue";

import { useRouter } from "vue-router";
export default {
  components: {
    Icon,
  },
  setup() {
    const showCategory = ref(true);
    const handleSidebarCategory = () => {
      showCategory.value = !showCategory.value;
    };
    const router = useRouter();
    const logout = () => {
      localStorage.removeItem("seller");
      router.push("/seller_index");
    };
    var userLogin = ref([]);
    const getUserFromLocalStorage = () => {
      const userData = localStorage.getItem("seller");
      if (userData) {
        userLogin.value = JSON.parse(userData);
      }
      return null;
    };
    getUserFromLocalStorage();
    return {
      handleSidebarCategory,
      showCategory,
      logout,
      userLogin,
    };
  },
};
</script>

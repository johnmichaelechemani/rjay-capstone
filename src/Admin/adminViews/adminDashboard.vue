<template>
  <div class="bg-sky-900 text-white py-5">
    <h1 class="text-lg pl-5">Admin</h1>
  </div>
  <div
    class="flex sm:hidden py-2 pl-2 sm:pl-8 bg-slate-200 gap-2 sm:text-sm text-xs"
  >
    <button
      @click="handleSidebarCategory"
      class="bg-slate-700/10 py-2 px-2 rounded-full font-semibold shadow"
    >
      <Icon icon="gg:menu-round" class="text-xl" />
    </button>
  </div>

  <div :class="showCategory ? 'flex relative' : ''">
    <div
      v-if="showCategory"
      class="text-base min-h-full w-72 shadow absolute z-10 sm:relative font-medium bg-gray-50 border border-r-slate-700/10"
    >
      <div class="h-screen">
        <p class="px-3 pt-5 text-sky-800">Manage Account</p>
        <hr class="my-2" />
        <div>
          <h1 class="font-semibold text-base text-sky-900 bg-slate-800/10 p-2">
            Seller
          </h1>
          <div class="px-2 text-sm font-medium py-2">
            <hr class="my-1" />
            <RouterLink to="/users">Sellers</RouterLink>

            <hr class="my-1" />
          </div>
        </div>
        <hr class="my-2" />
        <h1 class="font-semibold text-base text-sky-900 bg-slate-800/10 p-2">
          Customers
        </h1>
        <hr class="my-2" />
      </div>
    </div>

    <div class="bg-white cursor-pointer">
      <div class="mx-auto max-w-2xl px-4 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <div>
          <h2 class="md:text-2xl text-lg font-bold tracking-tight text-sky-900">
            Dashboard
          </h2>
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
export default {
  components: {
    Icon,
  },
  setup() {
    const showCategory = ref(true);
    const handleSidebarCategory = () => {
      showCategory.value = !showCategory.value;
    };
    return {
      handleSidebarCategory,
      showCategory,
    };
  },
};
</script>

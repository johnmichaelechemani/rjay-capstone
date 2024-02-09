<template>
  <div class="flex">
    <div v-if="isShow">
      <sellerSidebar />
    </div>
    <div class="relative">
      <div
        @click="showSidebarMenu"
        class="absolute left-3 top-3 bg-slate-500/10 p-2 rounded-full shadow"
      >
        <Icon icon="oui:menu-left" class="text-lg" />
      </div>
    </div>
    <div class="absolute right-5 top-2">
      <div class="p-1 bg-slate-500/10 shadow rounded-full relative">
        <Icon
          icon="material-symbols-light:notifications-outline"
          class="text-2xl"
        />
        <div class="absolute top-0 right-0">
          <Icon icon="icon-park-outline:dot" class="text-red-500" />
        </div>
      </div>
    </div>
    <router-view> </router-view>
  </div>
</template>
<script>
import sellerSidebar from "../sellersComponents/sellerSidebar.vue";
import { Icon } from "@iconify/vue";
import { ref } from "vue";
export default {
  components: {
    sellerSidebar,
    Icon,
  },
  setup() {
    const isShow = ref(true);
    const showSidebarMenu = () => {
      isShow.value = !isShow.value;
    };
    return {
      showSidebarMenu,
      isShow,
    };
  },
};
</script>

<template>
  <div class="flex">
    <sellerSidebar />
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
export default {
  components: {
    sellerSidebar,
    Icon,
  },
};
</script>

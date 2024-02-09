<template>
  <div class="flex">
    <sellerSidebar />
    <router-view> </router-view>
  </div>
</template>
<script>
import sellerSidebar from "../sellersComponents/sellerSidebar.vue";
export default {
  components: {
    sellerSidebar,
  },
};
</script>

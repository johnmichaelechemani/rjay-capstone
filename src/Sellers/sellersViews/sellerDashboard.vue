<template>
  <div class="flex">
    <div>
      <sellerSidebar />
    </div>
    <div>
      <router-view> </router-view>
    </div>
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

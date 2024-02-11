<template>
  <div class="mx-4">
    <div class="py-3 px-10 font-bold text-2xl text-slate-700">
      <h1>Transactions</h1>
    </div>
    <div class="my-3">
      <div
        class="border rounded-full w-60 shadow flex justify-between items-center px-4"
      >
        <input
          type="text"
          placeholder="Search Stock or Orders"
          class="outline-none placeholder:text-sm placeholder:font-light py-2 pl-2 w-full rounded-full"
        />
        <Icon icon="carbon:search" class="text-xl text-slate-500 my-2 ml-2" />
      </div>
    </div>
  </div>
</template>
<script>
import { Icon } from "@iconify/vue";
export default {
  components: {
    Icon,
  },
};
</script>

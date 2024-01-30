<template>
  <div
    v-if="isVisible"
    class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur overflow-y-auto h-full w-full z-50"
    @click="close"
  >
    <div
      class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
      @click.stop
    >
      <div class="mt-3 text-center">
        <div class="mt-2">
          <input
            type="text"
            class="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            v-model="searchQuery"
            placeholder="Search..."
            autofocus
            @keyup.enter="emitSearch"
          />
        </div>
        <div class="items-center px-4 py-3 flex justify-between"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isVisible: {
      type: Boolean,
      required: true,
    },
  },
  data() {
    return {
      searchQuery: "",
    };
  },
  methods: {
    emitSearch() {
      this.$emit("search", this.searchQuery);
      this.searchQuery = ""; // Reset the search query after emitting
    },
    close() {
      this.$emit("update:isVisible", false);
    },
  },
};
</script>

<style>
/* Define the blur effect */
.backdrop-blur {
  backdrop-filter: blur(
    2px
  ); /* Adjust the pixel value to increase or decrease the blur effect */
}
</style>

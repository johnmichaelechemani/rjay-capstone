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
          <form @submit.prevent="performSearch" method="get">
            <input
              type="text"
              class="border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              v-model="searchQuery"
              placeholder="Search..."
              required
              v-focus="isVisible"
              @keyup.enter="closeModalOnEnter"
            />
          </form>
        </div>
        <div class="items-center px-4 py-3 flex justify-between"></div>
      </div>
      <div>
        <h1 class="font-semibold text-base text-sky-900">Recent searches</h1>
        <div v-if="recentSearches.length > 0">
          <p
            v-for="(term, index) in recentSearches"
            :key="index"
            class="px-3 text-sm py-1 my-1 border border-slate-700/10 rounded-md cursor-pointer"
            @click="reSearch(term)"
          >
            {{ term }}
          </p>
        </div>
        <p v-else>No recent searches</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { computed, onMounted, ref, watch } from "vue";
import Fuse from "fuse.js";

export default {
  props: {
    isVisible: {
      type: Boolean,
      required: true,
    },
  },
  emits: ["update:isVisible", "search-completed"], // Declare emitted events here
  directives: {
    focus: {
      // Custom directive to autofocus input field
      mounted(el) {
        el.focus();
      },
    },
  },
  setup(props, { emit }) {
    const searchQuery = ref("");
    const products = ref([]);

    const recentSearches = ref(
      JSON.parse(localStorage.getItem("recentSearches")) || []
    );

    const options = {
      keys: ["product_name", "product_description"],
      threshold: 0.2,
    };

    const fetchAllProducts = async () => {
      try {
        const url =
          "http://localhost/Ecommerce/vue-project/src/backend/search.php";
        const response = await axios.post(url);
        products.value = response.data;
      } catch (error) {
        console.error(error);
      }
    };

    onMounted(() => {
      fetchAllProducts();
      const storedSearches =
        JSON.parse(localStorage.getItem("recentSearches")) || [];
      recentSearches.value = storedSearches;
    });

    const fuse = computed(() => new Fuse(products.value, options));

    const performSearch = () => {
      const trimmedQuery = searchQuery.value.trim();
      if (!trimmedQuery) {
        return products.value;
      }
      const results = fuse.value.search(trimmedQuery);
      emit(
        "search-completed",
        results.map((result) => result.item)
      );
      updateRecentSearches(trimmedQuery); // Pass the trimmed query
      return results.map((result) => result.item);
    };

    const updateRecentSearches = (query) => {
      const trimmedQuery = query.trim();

      // Remove the trimmed query from the existing recent searches array
      const updatedSearches = recentSearches.value.filter(
        (term) => term !== trimmedQuery
      );

      // Add the trimmed query to the beginning of the updated array
      updatedSearches.unshift(trimmedQuery);

      // Limit to the last 3 entries
      recentSearches.value = updatedSearches.slice(0, 3);

      // Update localStorage
      localStorage.setItem(
        "recentSearches",
        JSON.stringify(recentSearches.value)
      );
    };

    watch(searchQuery, (newValue, oldValue) => {
      if (newValue.trim() !== oldValue.trim()) {
        performSearch();
      }
    });

    const closeModalOnEnter = () => {
      close();
    };

    const close = () => {
      emit("update:isVisible", false);
    };

    const reSearch = (term) => {
      searchQuery.value = term;
      performSearch(); // Assuming performSearch can be called directly
      close(); // Optionally close the modal after searching
    };

    return {
      searchQuery,
      performSearch, // Now using performSearch as a method instead of a computed property
      close,
      closeModalOnEnter,
      reSearch,
      recentSearches,
    };
  },
};
</script>

<style>
.backdrop-blur {
  backdrop-filter: blur(2px);
}
</style>
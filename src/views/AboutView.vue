
<template>
  <Header />
  <SearchModal />
  <div>
    <!-- Your other template content -->
    <button @click="showSearch = true">Open Search</button>
    <search-modal :is-visible="showSearch" @search="handleSearch" @update:isVisible="showSearch = $event"></search-modal>
  </div>
</template>
<script>
import SearchModal from '@/components/SearchModal.vue';
import Header from "@/components/Header.vue";
import Products from "@/components/Products.vue";
export default {
components: {
    Header,
    SearchModal,
},

data() {
    return {
      showSearch: false
    };
  },
  methods: {
    handleSearch(query) {
      console.log('Search for:', query);
      // Implement your search logic here
    }
  }
}
</script>
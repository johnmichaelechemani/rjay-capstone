<template>
  <div>
    <!-- after navigator -->
    <div class="flex py-2 pl-2 sm:pl-8 bg-slate-200 gap-2 sm:text-sm text-xs">
      <button
        @click="handleSidebarCategory"
        class="bg-slate-700/10 py-2 px-4 rounded-md font-semibold shadow"
      >
        Category
      </button>
      <RouterLink
        to="/home"
        :class="{
          'bg-sky-700 text-white': $route.path === '/home',
        }"
        class="bg-slate-700/10 py-2 px-4 rounded-md font-semibold shadow"
        >Home</RouterLink
      >
    </div>
  </div>

  <div :class="showCategory ? 'flex relative' : ''">
    <div
      v-if="showCategory"
      class="text-base h-full w-72 shadow absolute z-10 sm:relative font-medium bg-gray-50 border border-r-slate-700/10"
    >
      <div class="h-screen">
        <p class="px-3 pt-5 text-sky-800">Catergories</p>
        <hr class="my-2" />
        <div v-for="item in categories" :key="item.category_id">
          <div
            v-for="(cat, index) in item"
            :key="index"
            class="mx-3"
            @click="filterByCategory(cat.category_id)"
          >
            <button class="py-1 text-sm my-1 px-2 rounded-md">
              {{ cat.name }}
            </button>
          </div>
        </div>
        <hr class="my-2" />
      </div>
    </div>
    <div class="bg-white cursor-pointer">
      <div class="mx-auto max-w-2xl px-4 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <h2 class="md:text-2xl text-lg font-bold tracking-tight text-sky-900">
          Popular products
        </h2>

        <div
          class="mt-6 grid grid-cols-2 gap-x-8 gap-y-8 md:grid-cols-3 lg:grid-cols-4 xl:gap-x-8"
        >
          <div
            @click="showModal(product)"
            v-for="product in products"
            :key="product.product_id"
            class="group relative border-2 border-zinc-300 rounded-2xl p-1 sm:p-2 overflow-hidden"
          >
            <div
              class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-sky-900 lg:aspect-none group-hover:opacity-75 lg:h-44"
            >
              <img
                :src="'data:image/png;base64,' + product.image"
                :alt="product.imageAlt"
                class="h-32 w-full object-center lg:h-44 lg:w-full"
              />
              <Icon
                icon="ph:heart-light"
                class="heart-icon"
                @click="onHeartClick(product)"
              />
            </div>
            <div class="mt-24 text-xs sm:text-sm">
              <div class="absolute bottom-0 w-full inset-x-0 rounded-b-md p-2">
                <div class="flex flex-col space-y-2 pl-2">
                  <h3 class="text-sky-900 font-bold">
                    <a :href="product.href">
                      {{ product.name }}
                    </a>
                  </h3>
                  <p class="font-medium">₱{{ product.price }}</p>
                  <div class="mt-1">
                    <span
                      v-for="star in getStars(product.ratings)"
                      :key="star.id"
                      :class="{
                        'star-colored': star.colored,
                        'star-grey': !star.colored,
                      }"
                      >&#9733;</span
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
          <product-modal
            :is-visible="isModalVisible"
            :product="selectedProduct"
            @update:isVisible="isModalVisible = $event"
          ></product-modal>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";
import { Icon } from "@iconify/vue";
import ProductModal from "@/components/ProductModal.vue";

export default {
  components: {
    Icon,
    ProductModal,
  },

  setup() {
    const products = ref([]);
    const isModalVisible = ref(false);
    const selectedProduct = ref(null);
    const showCategory = ref(false);
    const categories = ref([]);

    // get categories
    const getCategories = async () => {
      try {
        const response = await axios.get(
          "http://localhost/Ecommerce/vue-project/src/backend/categories.php"
        );
        categories.value = response.data;
        console.log(categories.value);
      } catch (error) {
        console.error("Error fetching categories: ", error);
      }
    };

    const handleSidebarCategory = () => {
      showCategory.value = !showCategory.value;
    };

    const showModal = (product) => {
      selectedProduct.value = product;
      isModalVisible.value = true;
    };

    const fetchProducts = async () => {
      try {
        const response = await axios.get(
          "http://localhost/Ecommerce/vue-project/src/backend/api.php"
        );
        console.log("API Response Data:", response.data);
        products.value = response.data;
      } catch (error) {
        console.error("Error fetching products: ", error);
      }
    };

    const getStars = (averageRating) => {
      const totalStars = 5;
      const roundedRating = Math.round(averageRating);
      return Array.from({ length: totalStars }, (_, i) => {
        return { id: i, colored: i < roundedRating };
      });
    };

    // filter by category
    const filterByCategory = (id) => {
      const f = products.value.map((item) => item.category_id === id);
      products.value = f;
      console.log(id);
    };

    onMounted(fetchProducts(), getCategories());

    const onHeartClick = (product) => {
      // Handle the heart icon click event
      console.log("Heart clicked for product:", product.id);
    };

    return {
      products,
      getStars,
      onHeartClick,
      isModalVisible,
      selectedProduct,
      showModal,

      handleSidebarCategory,
      showCategory,
      categories,

      filterByCategory,
    };
  },
};
</script>

<style scoped>
.star-colored {
  color: gold;
}
.star-grey {
  color: grey;
}
.heart-icon {
  position: absolute;
  top: 8px;
  right: 8px;
  font-size: 24px;
  color: black; /* Color of the heart icon */
  cursor: pointer;
  user-select: none;
  background-color: #e2e8f0; /* bg-slate-200 color */
  border-radius: 50%;
  padding: 4px;
}
</style>

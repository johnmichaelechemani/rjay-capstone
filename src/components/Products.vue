<template>
  <div>
    <Header @search-completed="handleSearchCompleted"></Header>
  </div>
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
        to="/"
        class="bg-sky-700 py-2 text-slate-100 px-4 rounded-md font-semibold shadow"
        >Home</RouterLink
      >
    </div>
  </div>

  <div :class="showCategory ? 'flex relative' : ''">
    <div
      v-if="showCategory"
      class="text-base h-full w-72 shadow absolute z-10 sm:relative font-medium bg-gray-50 border border-r-slate-700/10"
    >
      <div class="min-h-screen">
        <p class="px-3 pt-5 pb-3 text-sm text-sky-800">Catergories</p>
        <div>
          <button
            @click="fetchProducts"
            class="px-5 bg-slate-500/10 w-full py-2 text-sm hover:bg-slate-500/20 text-left mb-3"
          >
            All
          </button>
        </div>
        <div v-for="item in categories" :key="item.category_id">
          <div
            v-for="(cat, index) in item"
            :key="index"
            class="mx-3 hover:bg-slate-700/10 rounded-md transition"
            @click="filterByCategory(cat.category_id, cat.category_name)"
          >
            <button class="py-1 text-xs my-1 px-2 rounded-md">
              {{ cat.category_name }}
            </button>
          </div>
        </div>
        <hr class="my-2" />

        <!-- Stores filter -->
        <div>
          <h1 class="text-sm px-3 text-sky-800">Stores</h1>
          <div
            v-for="name in storeName"
            :key="name.store_id"
            class="mx-3 hover:bg-slate-700/10 rounded-md transition"
            @click="filterbyStoreName(name.store_id)"
          >
            <button
              class="py-2 text-sm flex gap-2 justify-start text-blue-500 items-center capitalize my-1 px-2 rounded-md"
            >
              <Icon icon="fa-solid:store" class="text-lg" />
              <span class="font-semibold"> {{ name.store_name }}</span>
            </button>
          </div>
        </div>
        <hr class="my-2" />

        <!-- Price Range -->
        <div class="px-3 py-2">
          <h1 class="text-sm text-sky-800 mb-2">Price Range</h1>
          <div class="flex gap-2 mb-3">
            <input
              v-model="minPrice"
              type="number"
              placeholder="Min"
              class="border p-1 rounded text-sm w-full"
            />
            <input
              v-model="maxPrice"
              type="number"
              placeholder="Max"
              class="border p-1 rounded text-sm w-full"
            />
          </div>
          <button
            @click="filterByPrice"
            class="bg-sky-700 py-2 text-slate-100 px-4 rounded-md font-semibold shadow w-full"
          >
            Apply
          </button>
        </div>
        <hr class="my-2" />

        <!-- Sidebar Rating Filters -->
        <div>
          <h1 class="text-sm px-3 text-sky-800">Ratings</h1>
          <div class="mx-3 text-xs">
            <template v-for="rating in 5" :key="`rating-${6 - rating}`">
              <div
                @click="filterByRating(6 - rating)"
                class="py-2 px-3 hover:bg-slate-700/10 rounded-md transition flex items-center"
              >
                <div class="flex items-center">
                  <template v-for="star in 6 - rating" :key="`star-${star}`">
                    <span class="star-colored">&#9733;</span>
                  </template>
                  <template
                    v-for="emptyStar in rating - 1"
                    :key="`empty-star-${emptyStar}`"
                  >
                    <span class="star-grey">&#9733;</span>
                  </template>
                </div>
                <span v-if="6 - rating < 5">&nbsp; And Up</span>
                <span v-else></span>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white cursor-pointer w-full">
      <div class="px-10 py-10">
        <div>
          <h2 class="md:text-2xl text-lg font-bold tracking-tight text-sky-900">
            {{ selectedCategoryName || "Popular Products" }}
          </h2>
        </div>

        <div
          class="mt-6 grid grid-cols-2 gap-x-4 gap-y-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 xl:gap-x-4"
        >
          <div
            @click="showModal(product)"
            v-for="product in products"
            :key="product.product_id"
            class="group relative bg-gradient-to-tr from-blue-500 via-violet-500 to-orange-500 rounded-xl p-[1px] overflow-hidden hover:shadow-lg hover:shadow-blue-500/50 transition"
          >
            <div class="bg-slate-100 w-full h-full rounded-xl">
              <div
                class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-xl bg-sky-900 lg:aspect-none group-hover:opacity-75 lg:h-44"
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
                <div
                  class="absolute bottom-0 w-full inset-x-0 rounded-b-md p-2"
                >
                  <div class="flex flex-col space-y-2 pl-2">
                    <h3 class="text-sky-900 font-bold">
                      <a :href="product.href">
                        {{ product.product_name }}
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
          </div>
          <product-modal
            :is-visible="isModalVisible"
            :product="selectedProduct"
            :specifications="spec_data"
            @update:isVisible="isModalVisible = $event"
          ></product-modal>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Header from "@/components/Header.vue";
import { ref, onMounted } from "vue";
import axios from "axios";
import { Icon } from "@iconify/vue";
import ProductModal from "@/components/ProductModal.vue";
export default {
  components: {
    Icon,
    ProductModal,
    Header,
  },
  name: "home",
  props: ["products"],

  setup(props) {
    const minPrice = ref(0);
    const maxPrice = ref(0);

    const products = ref(props.products || []);
    const isModalVisible = ref(false);
    const selectedProduct = ref(null);
    const showCategory = ref(true);
    const categories = ref([]);
    const selectedCategoryName = ref("");
    const spec_data = ref(null);
    const temp_data_for_ratings = ref([]);
    const temp_data_for_price = ref([]);
    const storeName = ref([]);
    const temp_data_for_store = ref([]);
    const temp_data_for_category = ref([]);

    const filterbyStoreName = async (storeID) => {
      temp_data_for_ratings.value = "";
      temp_data_for_price.value = "";
      temp_data_for_category.value = "";
      if (temp_data_for_store.value.length === 0) {
        temp_data_for_store.value = products.value;
      } else {
        products.value = temp_data_for_store.value;
      }
      //    console.log("store ID", storeID);
      // Assuming product.value was a typo and it should be products.value
      const filtered = products.value.filter((product) => {
        // Debugging: Check if IDs match
        const isMatch = product.store_id === storeID;
        //    console.log("Is Match:", isMatch);

        return isMatch;
      });

      //   console.log("Filtered products:", filtered);
      products.value = filtered;
    };

    //Get stores
    const GetStorename = async () => {
      try {
        const response = await axios.get(
          "http://localhost/Ecommerce/vue-project/src/backend/api.php?action=getStorename"
        );
        storeName.value = response.data;
        console.log(response.data);
      } catch (error) {
        console.error("Error fetching storenames: ", error);
      }
    };

    const filterByRating = (minRatingValue) => {
      temp_data_for_price.value = "";
      temp_data_for_store.value = "";
      temp_data_for_category.value = "";
      // Filter products based on rounded ratings
      if (temp_data_for_ratings.value.length === 0) {
        temp_data_for_ratings.value = products.value;
      } else {
        products.value = temp_data_for_ratings.value;
      }
      const filtered = products.value.filter((product) => {
        const roundedRating = Math.round(product.ratings); // Assuming product.ratings is a decimal
        return roundedRating >= minRatingValue;
      });
      products.value = filtered;
    };

    const filterByPrice = () => {
      // Assuming `props.products` contains all products you might want to filter
      // And these are already available in the `products` ref
      temp_data_for_ratings.value = "";
      temp_data_for_store.value = "";
      temp_data_for_category.value = "";
      if (temp_data_for_price.value.length === 0) {
        temp_data_for_price.value = products.value;
      } else {
        products.value = temp_data_for_price.value;
      }
      const filtered = products.value.filter((product) => {
        const price = parseFloat(product.price); // Ensure the price is a number
        return price >= minPrice.value && price <= maxPrice.value;
      });
      products.value = filtered;
    };

    const fetchSpecifications = async (productId) => {
      console.log("specs id", productId);
      try {
        const response = await axios.get(
          `http://localhost/Ecommerce/vue-project/src/backend/api.php?action=getProductSpecifications&id=${productId}`
        );
        return response.data;
      } catch (error) {
        console.error("Error fetching specifications: ", error);
        return null;
      }
    };

    const handleSearchCompleted = (product) => {
      products.value = product;
    };
    // get categories
    const getCategories = async () => {
      try {
        const response = await axios.get(
          "http://localhost/Ecommerce/vue-project/src/backend/api.php?action=fetchcategories"
        );
        categories.value = response.data;

        //    console.log(categories.value);
      } catch (error) {
        console.error("Error fetching categories: ", error);
      }
    };

    const handleSidebarCategory = () => {
      showCategory.value = !showCategory.value;
    };

    const showModal = async (product) => {
      const specifications = await fetchSpecifications(product.product_id);
      //   console.log("specs result in query", specifications);
      selectedProduct.value = { ...product, specifications };
      //   console.log("s afeifabsb", selectedProduct); // Combine product and specifications
      isModalVisible.value = true;
      //console.log(selectedProduct.value);
    };

    const fetchProducts = async () => {
      temp_data_for_ratings.value = "";
      temp_data_for_price.value = "";
      temp_data_for_store.value = "";
      temp_data_for_category.value = "";
      try {
        const response = await axios.get(
          "http://localhost/Ecommerce/vue-project/src/backend/api.php?action=getProducts"
        );
        //  console.log("API Response Data:", response.data);
        products.value = response.data;

        selectedCategoryName.value = "";
      } catch (error) {
        //     console.error("Error fetching products: ", error);
      }
    };

    const getStars = (averageRating) => {
      const totalStars = 5;
      const roundedRating = Math.round(averageRating);
      return Array.from({ length: totalStars }, (_, i) => {
        return { id: i, colored: i < roundedRating };
      });
    };
    // Use axios.post instead of axios.get, and pass data in the request body
    const filterByCategory = async (id, name) => {
      temp_data_for_ratings.value = "";
      temp_data_for_price.value = "";
      temp_data_for_store.value = "";
      if (temp_data_for_category.value.length === 0) {
        temp_data_for_category.value = products.value;
      } else {
        products.value = temp_data_for_category.value;
      }
      //   console.log("Category id", id);
      // Assuming product.value was a typo and it should be products.value
      const filtered = products.value.filter((product) => {
        // Debugging: Check if IDs match
        const isMatch = product.category_id === id;
        //  console.log("Is Match:", isMatch);

        return isMatch;
      });

      //   console.log("Filtered products:", filtered);
      products.value = filtered;
    };

    onMounted(fetchProducts(), getCategories(), GetStorename());

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
      fetchProducts,

      handleSidebarCategory,
      showCategory,
      categories,

      filterByCategory,
      selectedCategoryName,
      filterbyStoreName,

      handleSearchCompleted,

      minPrice,
      maxPrice,
      filterByPrice,
      filterByRating,
      temp_data_for_ratings,
      temp_data_for_price,
      temp_data_for_store,
      temp_data_for_category,

      fetchSpecifications,
      spec_data,
      GetStorename,
      storeName,
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
.star-rating-filter {
  background-color: #e2e8f0; /* Light blue background */
  border: none;
  cursor: pointer;
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 16px;
}
.star-rating-filter:hover {
  background-color: #cbd5e1; /* Darker shade for hover */
}
</style>

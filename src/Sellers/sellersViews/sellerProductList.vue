<template>
  <div class="mx-4">
    <div class="py-3 px-10 font-bold text-2xl text-slate-700">
      <h1>Product Lists</h1>
    </div>
    <div class="my-5">
      <div class="relative overflow-x-auto shadow rounded-md">
        <table
          class="w-full text-sm text-left rtl:text-right text-gray-900 rounded-md"
        >
          <thead
            class="text-xs text-slate-800 bg-slate-100/20 uppercase rounded-md"
          >
            <tr class="bg-gray-100/10 border-b border-gray-600/50">
              <th scope="col" class="px-6 py-3">Product ID</th>
              <th scope="col" class="px-6 py-3">Product Name</th>
              <th scope="col" class="px-6 py-3">Price</th>
              <th scope="col" class="px-6 py-3">Ratings</th>
              <th scope="col" class="px-6 py-3">Date uploaded</th>
              <th scope="col" class="px-6 py-3">Updated at</th>
              <th colspan="2" scope="col" class="px-6 py-3">Action</th>
            </tr>
          </thead>
          <tbody class="">
            <tr
              v-for="item in Products"
              :key="item.id"
              class="bg-gray-100/10 border-b border-gray-600/50"
            >
              <td scope="col" class="px-6 py-3">{{ item.product_id }}</td>
              <td
                scope="row"
                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
              >
                {{ item.product_name }}
              </td>
              <td class="px-6 py-4">{{ item.price }}</td>
              <td class="px-6 py-4">{{ item.ratings }}</td>
              <td class="px-6 py-4">{{ item.created_at }}</td>
              <td class="px-6 py-4">{{ item.updated_at }}</td>
              <td class="px-6 py-4">
                <button @click="deleteProduct(item.product_id)">
                  <Icon
                    icon="material-symbols:delete"
                    class="text-lg text-red-500"
                  />
                </button>
              </td>
              <td class="px-6 py-4">
                <button @click="editProduct(item.product_id)">
                  <Icon
                    icon="material-symbols:edit"
                    class="text-lg text-green-500"
                  />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
<script>
// YourComponent.vue <script> part
import { ref, onMounted } from "vue";
import { Icon } from "@iconify/vue";
import axios from "axios";
import { userLogin, getUserFromLocalStorage } from "@/scripts/Seller"; // Adjust the path as necessary

export default {
  components: {
    Icon,
  },
  setup() {
    const Products = ref([]);

    const deleteProduct = (deleteId) => {
      console.log(deleteId);
      if (confirm("Are you sure you want to delete this product?")) {
        console.log(deleteId);
      } else {
        console.log("delete canceled");
      }
    };
    const editProduct = (editId) => {
      console.log(editId);
    };

    // Now userLogin is directly accessible here, and it's reactive
    onMounted(() => {
      getUserFromLocalStorage(); // Initialize userLogin from localStorage when component mounts
      fetchProducts(); // Then fetch orders
    });

    const fetchProducts = async () => {
      console.log("seller ", userLogin.value.store_id);
      try {
        const response = await axios.post(
          "http://localhost/Ecommerce/vue-project/src/backend/seller/sellerApi.php?action=getProducts",
          {
            store_id: userLogin.value.store_id,
          }
        );
        Products.value = response.data;
        console.log("Products ", Products.value);
      } catch (error) {
        console.error("Error fetching orders:", error);
      }
    };

    const editData = (id) => {
      console.log(id);
    };

    return {
      Products,
      editData,

      editProduct,
      deleteProduct,
    };
  },
};
</script>

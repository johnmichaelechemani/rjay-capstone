<template>
  <div class="mx-4">
    <div class="py-3 px-10 font-bold text-2xl text-slate-700">
      <h1>Product Lists</h1>
    </div>
    <div class="">
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
              <th scope="col" class="px-6 py-3">Shipping Fee</th>
              <th scope="col" class="px-6 py-3">Ratings</th>
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
              <td class="px-6 py-4">{{ item.shipping_fee }}</td>
              <td class="px-6 py-4">{{ item.ratings }}</td>
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
  <!-- edit modal -->
  <div
    v-if="showEditModal"
    class="fixed inset-0 bg-gray-400/50 z-40 flex justify-center items-center"
  >
    <div
      class="bg-slate-200 w-full max-w-md mx-auto my-8 overflow-auto rounded-lg shadow-lg"
      style="max-height: 90vh"
    >
      <div class="py-2 px-5">
        <h1 class="py-5 text-2xl font-semibold text-gray-700">Edit Product</h1>

        <!-- Form Content -->
        <div class="space-y-2">
          <!-- Product Name -->
          <div>
            <label for="productName" class="text-sm">Product Name:</label>
            <input
              id="productName"
              type="text"
              v-model="product_name"
              class="p-2 rounded-md w-full"
            />
          </div>
          <!-- Image Upload -->
          <div class="py-2">
            <label for="productImage" class="block text-sm"
              >Product Image:</label
            >
            <input
              id="productImage"
              type="file"
              @change="handleImageChange"
              class="p-2 rounded-md w-full"
            />
            <!-- Optionally display the selected image -->
            <div v-if="showimage" class="mt-2">
              <img
                :src="image"
                class="max-h-40 max-w-full rounded-md shadow"
                :alt="product_name"
              />
            </div>
            <div v-else class="mt-2">
              <img
                :src="'data:image/png;base64,' + image"
                class="max-h-40 max-w-full rounded-md shadow"
                :alt="product_name"
              />
            </div>
          </div>
          <div class="py-2">
            <p for="" class="text-sm">Price:</p>
            <input
              type="number"
              v-model="product_price"
              class="p-2 rounded-md w-full"
            />
          </div>
          <div class="py-2">
            <p for="" class="text-sm">Product Description:</p>
            <input
              type="text"
              v-model="product_description"
              class="p-2 rounded-md w-full"
            />
          </div>
          <div class="py-2">
            <p for="" class="text-sm">Shipping Fee:</p>
            <input
              type="number"
              v-model="shipping_fee"
              class="p-2 rounded-md w-full"
            />
          </div>
          <div class="py-2">
            <p for="" class="text-sm">Stocks:</p>
            <input
              type="number"
              v-model="quantity"
              class="p-2 rounded-md w-full"
            />
          </div>

          <h1>Specifications</h1>
          <div
            v-for="(spec, index) in specifications"
            :key="index"
            class="flex flex-col md:flex-row md:items-center gap-4 mb-4"
          >
            <div class="flex-1">
              <label :for="'specKey-' + index" class="block text-sm"
                >Spec Key:</label
              >
              <input
                type="text"
                v-model="spec.spec_key"
                class="p-2 rounded-md w-full"
                :id="'specKey-' + index"
              />
            </div>
            <div class="flex-1">
              <label :for="'specValue-' + index" class="block text-sm"
                >Spec Value:</label
              >
              <input
                type="text"
                v-model="spec.spec_value"
                class="p-2 rounded-md w-full"
                :id="'specValue-' + index"
              />
            </div>
            <button
              @click="removeSpecification(index)"
              class="bg-red-500 text-white p-2 rounded"
            >
              Remove
            </button>
          </div>

          <!-- Add Specification Button -->
          <button
            @click="addSpecification"
            class="px-4 py-2 bg-blue-400 text-white w-full rounded-md shadow my-4"
          >
            Add Specification
          </button>
        </div>

        <!-- Modal Actions -->
        <div class="flex justify-evenly my-5 gap-5 items-center">
          <button
            @click="closeEditModal()"
            class="px-4 py-2 bg-gray-400/20 text-slate-700 w-full rounded-md shadow"
          >
            Cancel
          </button>
          <button
            @click="handleEditProduct"
            class="px-4 py-2 bg-green-400/20 text-green-700 hover:bg-green-500/25 w-full rounded-md shadow"
          >
            Edit
          </button>
        </div>
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
    const refreshPage = () => {
      location.reload(true);
    };
    const Products = ref([]);

    const deleteProduct = async (deleteId) => {
      console.log(deleteId);
      if (confirm("Are you sure you want to delete this product?")) {
        console.log(deleteId);
        try {
          const response = await axios.post(
          "http://localhost/Ecommerce/vue-project/src/backend/seller/sellerApi.php?action=deleteProduct",
          {
            id: deleteId,
          }
          );
          console.log("delet message:", response.data);
        } catch (error) {
          console.error("Error deleting:", error);
        }
        fetchProducts();
      } else {
        console.log("delete canceled");
      }
    };

    // get product is for editing

    const showEditModal = ref(false);

    const closeEditModal = () => {
      showEditModal.value = false;
    };
    const productEditable = ref({});

    //info to update
    const product_name = ref("");
    const product_description = ref("");
    const product_price = ref("");
    const shipping_fee = ref("");
    const quantity = ref("");
    const image = ref("");
    const showimage = ref(false);

    const handleImageChange = (event) => {
      const file = event.target.files[0];
      showimage.value = true;
      if (!file) {
        return;
      }

      const reader = new FileReader();
      reader.onload = (e) => {
        image.value = e.target.result;
      };
      reader.readAsDataURL(file);
    };

    const specifications = ref([{ spec_key: "", spec_value: "" }]);
    const editProductId = ref("");

    // Method to add a new specification entry
    const addSpecification = () => {
      specifications.value.push({ spec_key: "", spec_value: "" });
    };

    // Method to remove a specification entry by index
    const removeSpecification = (index) => {
      specifications.value.splice(index, 1);
    };
    const editProduct = async (editId) => {
      try {
        const response = await axios.post(
          "http://localhost/Ecommerce/vue-project/src/backend/seller/sellerApi.php?action=getSpecs",
          {
            id: editId,
          }
        );
        specifications.value = response.data;
        console.log("specs ", specifications.value);
      } catch (error) {
        console.error("Error fetching Specs:", error);
      }

      editProductId.value = editId;
      const productToEdit = Products.value.find(
        (product) => product.product_id === editId
      );

      if (productToEdit) {
        // Set the found product to the productEditable ref
        productEditable.value = { ...productToEdit };
        // Show the edit modal
        showEditModal.value = true;
        console.log("edit products:", productEditable.value);
        product_name.value = productEditable.value.product_name;
        product_price.value = productEditable.value.price;
        product_description.value = productEditable.value.product_description;
        shipping_fee.value = productEditable.value.shipping_fee;
        quantity.value = productEditable.value.quantity;
        image.value = productEditable.value.image;
      }
    };

    const handleEditProduct = async () => {
      console.log(editProductId.value);
      console.log(product_name.value);
      console.log(product_price.value);
      console.log(product_description.value);
      console.log(shipping_fee.value);
      console.log(quantity.value);
      console.log(specifications.value);
      console.log(image.value);

      try {
        const response = await axios.put(
          "http://localhost/Ecommerce/vue-project/src/backend/seller/sellerApi.php?action=editProductsInfo",
          {
            product_id: editProductId,
            product_name: product_name.value,
            product_price: product_price.value,
            product_description: product_description.value,
            shipping_fee: shipping_fee.value,
            quantity: quantity.value,
            image: image.value,
            specifications: specifications.value,
          }
        );
        console.log("response after edit ", response.data);
      } catch (error) {
        console.error("Error fetching Specs:", error);
      }
      refreshPage();
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

      showEditModal,
      closeEditModal,
      handleEditProduct,
      specifications,
      addSpecification,
      removeSpecification,
      handleImageChange,
      showimage,

      product_name,
      product_price,
      product_description,
      shipping_fee,
      quantity,
      image,
    };
  },
};
</script>

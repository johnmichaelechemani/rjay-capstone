<template>
  <div class="mx-4">
    <div class="py-3 px-10 font-bold text-2xl text-slate-700">
      <h1>Add Products</h1>
    </div>
    <div>
      <div class="flex justify-end">
        <button
          @click="saveProduct"
          class="bg-blue-400/20 text-blue-600 px-3 font-medium text-sm py-2 rounded-full mb-2 shadow"
        >
          Save Product
        </button>
      </div>
    </div>
    <div class="gap-5">
      <!-- Add Category Section -->
      <div class="my-4">
        <h1 class="text-base font-medium text-gray-700">Add New Category:</h1>
        <div class="my-2">
          <input
            type="text"
            v-model="newCategory.category_name"
            placeholder="New Category Name"
            class="w-full p-2 rounded-md placeholder:text-sm border outline-none"
          />
        </div>
        <div class="my-2">
          <textarea
            v-model="newCategory.category_description"
            placeholder="Category Description"
            rows="3"
            class="w-full p-2 placeholder:text-sm rounded-md border outline-none"
          ></textarea>
        </div>
        <button
          @click="addNewCategory"
          class="px-4 py-1 bg-blue-500 hover:bg-blue-600 transition text-gray-100 rounded-md shadow"
        >
          Add Category
        </button>
        <p
          v-if="message.content"
          :class="`mt-2 text-sm ${
            message.type === 'success' ? 'text-green-500' : 'text-red-500'
          }`"
        >
          {{ message.content }}
        </p>
      </div>
      <hr class="boarder" />
      <div
        class="bg-slate-400/10 rounded-md h-full w-1/2 text-slate-700 shadow my-5 p-4"
      >
        <div class="py-2">
          <label for="productImage" class="block text-sm">Product Image:</label>
          <input
            id="productImage"
            type="file"
            @change="UploadImage"
            class="p-2 rounded-md w-full"
          />
          <!-- Display the selected image if showimage is true -->
          <div v-if="showimage" class="mt-2">
            <img
              :src="image"
              alt="Uploaded Product Image"
              class="max-h-40 max-w-full rounded-md shadow"
            />
          </div>
          <!-- Display a placeholder or message if no image is selected/uploaded -->
          <div v-else class="mt-2">
            <span class="flex items-center">No Image Uploaded</span>
          </div>
        </div>
      </div>
      <div
        class="bg-slate-400/10 rounded-md h-full w-full text-slate-700 shadow"
      >
        <div class="p-2">
          <h1 class="text-base font-semibold">General Information</h1>
          <!-- category below -->
          <div class="my-4">
            <h1 class="text-sm font-medium">Category:</h1>
            <select
              class="w-full p-2 rounded-md my-1 border outline-none"
              v-model="selectedCategory"
            >
              <option disabled value="">Please select one</option>
              <option
                v-for="category in categories"
                :key="category.category_id"
                :value="category.category_id"
              >
                {{ category.category_name }}
              </option>
            </select>
          </div>
          <div class="my-4">
            <h1 class="text-sm font-medium">Product Name:</h1>
            <input
              type="text"
              class="w-full p-2 rounded-md my-1 border outline-none"
              v-model="productName"
            />
          </div>
          <div class="my-4">
            <h1 class="text-sm font-medium">Product Description:</h1>
            <textarea
              type="text"
              cols="10"
              rows="5"
              class="w-full p-2 rounded-md my-1 border outline-none"
              v-model="productDescription"
            />
          </div>
          <div class="my-4 flex gap-2 justify-start items-center">
            <div>
              <h1 class="text-sm font-medium">Price:</h1>
              <div
                class="flex justify-start items-center bg-slate-50 border rounded-md"
              >
                <p class="px-2 font-medium">₱</p>
                <input
                  type="number"
                  class="w-full p-2 bg-slate-50 rounded-md outline-none"
                  v-model="price"
                />
              </div>
            </div>
            <div>
              <h1 class="text-sm font-medium">Shipping fee:</h1>
              <div
                class="flex justify-start items-center bg-slate-50 border rounded-md"
              >
                <p class="px-2 font-medium">₱</p>
                <input
                  type="number"
                  class="w-full p-2 bg-slate-50 rounded-md outline-none"
                  v-model="shipping"
                />
              </div>
            </div>
          </div>
          <div class="my-4">
            <h1 class="text-sm font-medium">Stocks/Quantity:</h1>
            <input
              type="number"
              class="w-full p-2 rounded-md my-1 border outline-none"
              v-model="quantity"
            />
          </div>
          <!-- specifications here -->
          <div class="my-4">
            <h1 class="text-base font-semibold">Specifications</h1>
            <div
              v-for="(spec, index) in specifications"
              :key="index"
              class="flex gap-2 items-center my-2"
            >
              <input
                v-model="spec.Spec_key"
                type="text"
                placeholder="Key"
                class="p-2 rounded-md border outline-none"
              />
              <input
                v-model="spec.Spec_value"
                type="text"
                placeholder="Value"
                class="p-2 rounded-md border outline-none"
              />
              <button
                @click="removeSpec(index)"
                class="px-3 py-1 bg-red-500 text-white rounded"
              >
                Remove
              </button>
            </div>
            <button
              @click="addSpec"
              class="px-3 py-1 bg-green-500 text-white rounded"
            >
              Add Specification
            </button>
          </div>
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
    const specifications = ref([{ Spec_key: "", Spec_value: "" }]);

    const addSpec = () => {
      specifications.value.push({ Spec_key: "", Spec_value: "" });
    };

    const removeSpec = (index) => {
      specifications.value.splice(index, 1);
    };

    const productName = ref("");
    const productDescription = ref("");
    const price = ref(null);
    const shipping = ref(null);
    const quantity = ref(null);
    const saveProduct = async () => {
      // Implementation to save the product along with specifications
      console.log("selectedCategory:", selectedCategory.value);
      console.log("image:", image.value);
      console.log("Productname:", productName.value);
      console.log("productDescription:", productDescription.value);
      console.log("price:", price.value);
      console.log("shipping:", shipping.value);
      console.log("quantity:", quantity.value);
      console.log("Product and specifications saved:", specifications.value);
      console.log("Store ID:", userLogin.value.store_id);

      try {
        const response = await axios.post(
          "http://localhost/Ecommerce/vue-project/src/backend/seller/sellerApi.php?action=SaveProduct",
          {
            selectedCategory: selectedCategory.value,
            image: image.value,
            productName: productName.value,
            productDescription: productDescription.value,
            price: price.value,
            shipping: shipping.value,
            quantity: quantity.value,
            specifications: specifications.value,
            store_id: userLogin.value.store_id,
          }
        );
        console.log(response.data);
        refreshPage();
        // Optionally, clear form fields or perform additional actions upon successful save
      } catch (error) {
        console.error("Error saving product:", error);
      }
    };

    const selectedCategory = ref("");
    const categories = ref([]);
    const newCategory = ref({ category_name: "", category_description: "" });
    const message = ref({ content: "", type: "success" });

    const addNewCategory = async () => {
      if (newCategory.value.category_name.trim() === "") {
        message.value = {
          content: "Please enter a category name.",
          type: "error",
        };
        return;
      }
      try {
        const response = await axios.post(
          "http://localhost/Ecommerce/vue-project/src/backend/seller/sellerApi.php?action=AddCategory",
          {
            category_name: newCategory.value.category_name,
            category_description: newCategory.value.category_description,
          }
        );
        getCategories();
        message.value = {
          content: "new category added successfully",
          type: "success",
        };
      } catch (error) {
        console.error("Error adding new category:", error);
        message.value = {
          content: "There was an error while adding the category.",
          type: "error",
        };
      }
    };

    const getCategories = async () => {
      try {
        const response = await axios.get(
          "http://localhost/Ecommerce/vue-project/src/backend/seller/sellerApi.php?action=fetchcategories"
        );
        categories.value = response.data;
        console.log("categories ", categories.value);
      } catch (error) {
        console.error("Error fetching categories:", error);
      }
    };

    onMounted(() => {
      getUserFromLocalStorage();
      getCategories(); // Initialize userLogin from localStorage when component mounts
    });

    const refreshPage = () => {
      location.reload(true);
    };

    const showimage = ref(false);
    const image = ref("");
    const UploadImage = (event) => {
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

    return {
      UploadImage,
      showimage,
      image,
      specifications,
      addSpec,
      removeSpec,
      saveProduct,
      getCategories,
      categories,
      selectedCategory,
      newCategory,
      addNewCategory,
      message,

      productName,
      productDescription,
      price,
      shipping,
      quantity,
    };
  },
};
</script>

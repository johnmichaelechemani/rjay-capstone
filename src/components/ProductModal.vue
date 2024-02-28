<template>
  <div
    v-if="isVisible"
    class="fixed inset-0 flex items-center justify-center bg-gray-500 border-2 border-zinc-300 bg-opacity-75 z-20"
  >
    <div class="bg-white rounded-lg shadow-xl max-w-lg mx-auto p-6">
      <button
        @click="closeModal"
        class="float-end p-2 bg-slate-700/20 rounded-full"
      >
        <Icon icon="gravity-ui:xmark" class="text-lg hover:text-red-500" />
      </button>

      <div class="flex">
        <div class="flex-none w-48 relative">
          <img
            :src="'data:image/png;base64,' + product.image"
            :alt="product.name"
            class="absolute inset-0 w-full h-auto object-cover"
            loading="lazy"
          />
        </div>
        <form class="flex-auto p-6">
          <div class="flex flex-wrap">
            <div>
              <h1 class="flex-auto text-xl font-semibold text-gray-900">
                {{ product.product_name }}
              </h1>
              <div>
                <span class="text-base font-medium">{{
                  product.product_description
                }}</span>
              </div>
            </div>
            <div class="text-lg font-semibold text-black-500">
              ${{ finalQuantity }}
            </div>
            <div
              class="w-full flex gap-2 justify-start items-center text-sm font-medium text-black-700"
            >
              <span>In stock</span>
              <span class="text-blue-500">{{ product.quantity || "0" }}</span>
            </div>
            <div
              class="w-full flex gap-2 justify-start items-center text-sm font-medium text-black-700"
            >
              <span>from : </span>
              <span>{{ product.store_name }}</span>
            </div>
          </div>

          <div>
            <div>
              <form class="max-w-xs mx-auto my-2">
                <div class="relative flex items-center max-w-[8rem]">
                  <button
                    type="button"
                    @click="decrement"
                    :disabled="quantity === 1"
                    class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-s-lg px-2 h-8 focus:ring-gray-100 focus:ring-2 focus:outline-none"
                  >
                    <Icon icon="ic:baseline-minus" />
                  </button>
                  <input
                    type="text"
                    id="quantity-input"
                    v-model="quantity"
                    readonly
                    aria-describedby="helper-text-explanation"
                    class="bg-gray-50 border-x-0 border-gray-300 h-8 w-10 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block p-2"
                    placeholder="0"
                    required
                  />
                  <button
                    type="button"
                    @click="increment"
                    :disabled="quantity === 3"
                    class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-e-lg px-2 h-8 focus:ring-gray-100 focus:ring-2 focus:outline-none"
                  >
                    <Icon icon="tabler:plus" />
                  </button>
                </div>
              </form>
            </div>
            <div class="sm:flex text-sm justify-start items-center font-medium">
              <div class="flex-auto my-1 mx-2 flex space-x-4">
                <button
                  class="h-10 sm:w-40 px-6 hover:bg-slate-500/10 font-semibold rounded-md border border-black-800 text-gray-900"
                  type="button"
                  @click="addToCart(product.product_name, product.product_id)"
                >
                  <span class="text-xs sm:text-sm"> Add to cart</span>
                </button>
              </div>
              <button
                class="flex-none hover:text-red-400 transition flex items-center justify-center w-9 h-9 rounded-md text-slate-300 border border-slate-200"
                type="button"
                aria-label="Favorites"
                @click="heart"
                :class="{ 'text-red-400': isHeartRed }"
              >
                <Icon icon="ph:heart-fill" class="text-lg" />
              </button>
            </div>
          </div>
        </form>
      </div>
      <div class="text-sm">
        <span>specifications : </span>
        <ul>
          <li
            v-for="(spec, index) in product.specifications.specifications"
            :key="index"
          >
            {{ spec.spec_key }}: {{ spec.spec_value }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import { Icon } from "@iconify/vue";
import axios from "axios";
import { ref, watch } from "vue";
export default {
  props: {
    isVisible: Boolean,
    product: Object,
  },
  components: {
    Icon,
  },
  methods: {
    closeModal() {
      this.$emit("update:isVisible", false);
    },
  },
  setup(props, { emit }) {
    const quantity = ref(1);
    const finalQuantity = ref("");
    const isHeartRed = ref(false);

    const increment = () => {
      quantity.value = Math.min(Number(quantity.value) + 1, 3); // Ensure the quantity does not exceed 3
      finalQuantity.value = quantity.value * props.product.price;
    };
    watch(
      () => (props.product ? props.product.price : null),
      (newPrice) => {
        finalQuantity.value = newPrice || 0;
      }
    );

    const decrement = () => {
      quantity.value = Math.max(Number(quantity.value) - 1, 1); // Ensure the quantity does not go below 1
      finalQuantity.value = quantity.value * props.product.price;
    };
    const userLogin = ref([]);

    const getUserFromLocalStorage = () => {
      try {
        const userData = localStorage.getItem("user");

        if (userData !== null) {
          userLogin.value = JSON.parse(userData);
        }

        return null;
      } catch (error) {
        console.error(
          "Error while getting user data from local storage:",
          error
        );
        return error;
      }
    };
    getUserFromLocalStorage();
    const cart_id = userLogin.value.user_id;

    const addToCart = async (name, id) => {
      console.log(finalQuantity.value);
      console.log(name);
      console.log(id);
      console.log(quantity.value);
      console.log(cart_id);

      try {
        const response = await axios.post(
          "http://localhost/Ecommerce/vue-project/src/backend/api.php?action=addCart",
          {
            product_id: id,
            quantity: quantity.value,
            cart_id: cart_id,
          }
        );
        console.log(response.data);
        emit("update:isVisible", false);
      } catch (error) {
        console.error("Error adding to cart:", error);
      }
    };
    const heart = () => {
      isHeartRed.value = !isHeartRed.value;
      console.log("heart");
    };

    return {
      quantity,
      increment,
      decrement,
      finalQuantity,
      addToCart,
      heart,
      isHeartRed,
    };
  },
};
</script>

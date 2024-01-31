<template>
  <div
    v-if="isVisible"
    class="fixed inset-0 flex items-center justify-center bg-gray-500 border-2 border-zinc-300 bg-opacity-75 z-20"
  >
    <div class="bg-white rounded-lg shadow-xl max-w-lg mx-auto p-6">
      <div>
        <button
          @click="closeModal"
          class="float-end p-2 bg-slate-700/20 rounded-full"
        >
          <Icon icon="gravity-ui:xmark" class="text-lg hover:text-red-500" />
        </button>
      </div>
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
                {{ product.name }}
              </h1>
              <div>
                <span class="text-base font-medium">{{
                  product.description
                }}</span>
              </div>
            </div>
            <div class="text-lg font-semibold text-black-500">
              ${{ product.price }}
            </div>
            <div
              class="w-full flex-none text-sm font-medium text-black-700 py-2"
            >
              <span>In stock</span> <span class="text-blue-500">2</span>
            </div>
          </div>

          <div>
            <div>
              <form class="max-w-xs mx-auto my-2">
                <div class="relative flex items-center max-w-[8rem]">
                  <button
                    type="button"
                    @click="decrement"
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
                    class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-e-lg px-2 h-8 focus:ring-gray-100 focus:ring-2 focus:outline-none"
                  >
                    <Icon icon="tabler:plus" />
                  </button>
                </div>
              </form>
            </div>
            <div class="sm:flex text-sm justify-start items-center font-medium">
              <div class="flex-auto my-1 flex space-x-4">
                <button
                  class="h-10 px-6 hover:bg-slate-500/10 font-semibold rounded-md border border-black-800 text-gray-900"
                  type="button"
                >
                  Add to cart
                </button>
              </div>
              <button
                class="flex-none hover:text-red-400 transition flex items-center justify-center w-9 h-9 rounded-md text-slate-300 border border-slate-200"
                type="button"
                aria-label="Favorites"
              >
                <Icon icon="ph:heart-fill" class="text-lg" />
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { Icon } from "@iconify/vue";
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
  setup() {
    const quantity = ref(0);

    const increment = () => {
      // You can add validation here if needed
      quantity.value = Number(quantity.value) + 1;
      if (quantity.value > 3) {
        quantity.value = 3;
      }
    };

    const decrement = () => {
      // You can add validation here if needed
      quantity.value = String(Number(quantity.value) - 1);
      if (quantity.value < 1) {
        quantity.value = 1;
      }
    };

    return { quantity, increment, decrement };
  },
};
</script>

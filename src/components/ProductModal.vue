<template>
  <div
    v-if="isVisible"
    class="fixed inset-0 flex items-center justify-center bg-gray-500 border-2 border-zinc-300 bg-opacity-75 z-60"
  >
    <div class="bg-white rounded-lg shadow-xl max-w-lg mx-auto p-6">
      <div class="flex font-sans">
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
            <h1 class="flex-auto text-xl font-semibold text-gray-900">
              {{ product.name }}
            </h1>
            <div class="text-lg font-semibold text-black-500">
              ${{ product.price }}
            </div>
            <div
              class="w-full flex-none text-sm font-medium text-black-700 mt-2"
            >
              In stock
            </div>
          </div>
          <!-- ... existing code for size selection, etc. ... -->
          <div
            class="flex items-baseline mt-4 mb-6 pb-6 border-b border-slate-200"
          >
            <div class="space-x-2 flex text-sm">
              <label>
                <input
                  class="sr-only peer"
                  name="size"
                  type="radio"
                  value="xs"
                />
                <div
                  class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-700 peer-checked:font-semibold peer-checked:bg-slate-900 peer-checked:text-white"
                >
                  XS
                </div>
              </label>
              <label>
                <input
                  class="sr-only peer"
                  name="size"
                  type="radio"
                  value="s"
                />
                <div
                  class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-700 peer-checked:font-semibold peer-checked:bg-slate-900 peer-checked:text-white"
                >
                  S
                </div>
              </label>
              <label>
                <input
                  class="sr-only peer"
                  name="size"
                  type="radio"
                  value="m"
                  checked
                />
                <div
                  class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-700 peer-checked:font-semibold peer-checked:bg-slate-900 peer-checked:text-white"
                >
                  M
                </div>
              </label>
              <label>
                <input
                  class="sr-only peer"
                  name="size"
                  type="radio"
                  value="l"
                />
                <div
                  class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-700 peer-checked:font-semibold peer-checked:bg-slate-900 peer-checked:text-white"
                >
                  L
                </div>
              </label>
              <label>
                <input
                  class="sr-only peer"
                  name="size"
                  type="radio"
                  value="xl"
                />
                <div
                  class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-700 peer-checked:font-semibold peer-checked:bg-slate-900 peer-checked:text-white"
                >
                  XL
                </div>
              </label>
            </div>
          </div>
          <div class="flex space-x-4 mb-6 text-sm font-medium">
            <div class="flex-auto flex space-x-4">
              <button
                class="h-10 px-6 font-semibold rounded-md border border-black-800 text-gray-900"
                type="button"
              >
                Add to cart
              </button>
            </div>
            <button
              class="flex-none flex items-center justify-center w-9 h-9 rounded-md text-slate-300 border border-slate-200"
              type="button"
              aria-label="Favorites"
            >
              <!-- Favorite icon -->
              <svg
                width="20"
                height="20"
                fill="currentColor"
                aria-hidden="true"
              >
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                />
              </svg>
            </button>
          </div>
          <p class="text-sm text-slate-700">Free shipping</p>
        </form>
      </div>
      <button @click="closeModal" class="mt-4">Close</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isVisible: Boolean,
    product: Object,
  },
  methods: {
    closeModal() {
      this.$emit("update:isVisible", false);
    },
  },
};
</script>

<template>
  <!-- Top -->
  <div
    class="sm:flex justify-between py-4 px-2 sm:px-12 text-xs sm:text-sm cursor-pointer hidden"
  >
    <div class="flex place-items-center">
      <span>Need help? Call us: 09123456789</span>
    </div>
    <div class="flex items-center gap-3 hover:text-zinc-500">
      <Icon icon="fluent:vehicle-bus-24-regular" />
      <span>Track your order</span>
    </div>
  </div>
  <!-- Navigator -->
  <div
    class="text-xs sm:text-sm flex container-fluid bg-sky-900 p-2 sm:p-4 pl-2 sm:pl-12 place-items-center cursor-pointer"
  >
    <!-- kulang ng logo dito -->
    <div class="flex sm:ml-10 ml-0">
      <p class="text-sm sm:text-2xl font-bold text-white pr-4">Logo Here</p>
    </div>
    <!-- Search bar -->
    <div class="md:ml-12">
      <div
        class="flex justify-between bg-none lg:bg-white border rounded-full overflow-hidden"
      >
        <button
          @click="showSearch = true"
          class="w-50 lg:w-80 p-2 hover:bg-gray-200/20"
        >
          <div class="flex items-center text-white lg:text-black">
            <Icon
              class="text-2xl lg:ml-4"
              icon="material-symbols-light:search"
            />
            <span class="pl-10 hidden lg:flex"> Search any things... </span>
          </div>
        </button>

        <SearchModal
          :is-visible="showSearch"
          @update:isVisible="showSearch = $event"
          @search-completed="handleSearchCompleted"
        ></SearchModal>
      </div>
    </div>
    <!-- right nav -->
    <div
      class="hidden sm:flex ml-auto items-center gap-5 sm:mr-12 cursor-pointer"
    >
      <!-- sign in -->
      <div
        class="flex items-center gap-2 text-white hover:text-zinc-400"
        @click="Handlesignin"
      >
        <Icon icon="bi:person" class="text-lg" />
        <span>Sign in</span>
      </div>
      <!-- wishlist -->
      <div
        @click="showWishListFunction"
        class="flex items-center gap-2 text-white hover:text-zinc-400 relative"
      >
        <div>
          <Icon
            icon="radix-icons:dot-filled"
            class="text-xl text-red-500 absolute -top-2 left-0"
          />
        </div>
        <Icon icon="ph:heart-light" class="text-lg" />
        <span>Wishlist</span>
      </div>
      <!-- Cart -->
      <div
        @click="showCartFunction"
        class="flex items-center gap-2 text-white hover:text-zinc-400 relative"
      >
        <div>
          <Icon
            icon="radix-icons:dot-filled"
            class="text-xl text-red-500 absolute -top-2 left-0"
          />
        </div>
        <Icon icon="ion:cart-outline" class="text-lg" />
        <span>Cart</span>
      </div>
    </div>
    <!-- cart modal -->
    <div v-if="showCart" class="flex justify-center items-center">
      <div class="absolute top-32 right-3 z-50">
        <div
          class="bg-slate-300 border border-slate-900/20 shadow-lg px-3 py-2 w-96 rounded-lg"
        >
          <div class="flex justify-end">
            <div
              @click="closeCart"
              class="bg-slate-600/20 rounded-full text-red-500 shadow p-2"
            >
              <Icon icon="iconamoon:close-bold" />
            </div>
          </div>
          <div>
            <h1 class="font-semibold text-lg">Cart</h1>
          </div>
          <div
            class="p-2 bg-slate-500/10 h-96 overflow-scroll overflow-x-hidden rounded-md shadow-sm"
          >
            <div v-for="items in cartItemsValue" :key="items">
              <div class="flex gap-2 justify-between my-2">
                <div class="flex gap-2">
                  <img
                    :src="'data:image/png;base64,' + items.image"
                    alt=""
                    class="w-16 h-16 rounded-md"
                  />
                  <div>
                    <h1 class="font-semibold text-xs">
                      {{ items.product_name }}
                    </h1>
                    <p>{{ items.price }}</p>
                    <p class="text-xs">Qtty: <span>2</span></p>
                  </div>
                </div>

                <div>
                  <div class="gap-2 justify-start items-center">
                    <button
                      class="flex my-1 justify-center items-center gap-2 border text-red-500 p-2 rounded-md"
                    >
                      <Icon
                        icon="ic:round-delete"
                        class="text-lg text-red-500"
                      />Delete
                    </button>
                    <button
                      class="flex justify-center items-center gap-2 bg-blue-500 text-gray-100 p-2 rounded-md"
                    >
                      <Icon
                        icon="ic:outline-shopping-cart-checkout"
                        class="text-lg"
                      />Buy Now
                    </button>
                  </div>
                </div>
              </div>
              <hr />
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- cart modal -->
    <!-- wish modal -->
    <div v-if="showWishList" class="flex justify-center items-center">
      <div class="absolute top-32 right-3 z-50">
        <div
          class="bg-slate-300 border border-slate-900/20 shadow-lg px-3 py-2 w-96 rounded-lg"
        >
          <div class="flex justify-end">
            <div
              @click="closeWishList"
              class="bg-slate-600/20 rounded-full text-red-500 shadow p-2"
            >
              <Icon icon="iconamoon:close-bold" />
            </div>
          </div>
          <div>
            <h1 class="font-semibold text-lg">Wish List</h1>
          </div>
          <div class="p-2 bg-slate-500/10 rounded-md shadow-sm">
            <p>Intel Core i10 16th gen</p>
          </div>
        </div>
      </div>
    </div>
    <!-- wish modal -->

    <!-- Hamburger Button for Small Screens -->
    <div
      class="sm:hidden flex items-center text-white cursor-pointer ml-auto"
      @click="toggleSidebar"
    >
      <Icon icon="heroicons-solid:menu" class="text-4xl" />
    </div>

    <!-- Sidebar for Small Screens -->
    <div
      :class="{
        'translate-x-0': isSidebarOpen,
        'translate-x-full': !isSidebarOpen,
      }"
      class="sm:hidden fixed inset-y-0 right-0 bg-sky-900 bg-opacity-90 z-50 transition-transform duration-300 ease-in-out"
    >
      <div class="flex flex-col items-end p-4 pl-16">
        <!-- Close Button -->
        <div class="cursor-pointer text-white" @click="toggleSidebar">
          <Icon icon="heroicons-solid:x" class="text-2xl" />
        </div>

        <!-- Sidebar Content -->
        <div class="flex flex-col items-end mt-4 gap-2">
          <!-- track your order -->
          <div class="flex items-center gap-2 text-white hover:text-zinc-400">
            <Icon icon="fluent:vehicle-bus-24-regular" />
            <span>Track your order</span>
          </div>

          <!-- Wishlist -->
          <div
            @click="showWishListFunction"
            class="flex items-center gap-2 text-white hover:text-zinc-400"
          >
            <Icon icon="ph:heart-light" />
            <span>Wishlist</span>
          </div>

          <!-- Cart -->
          <div
            @click="showCartFunction"
            class="flex items-center gap-2 text-white hover:text-zinc-400"
          >
            <Icon icon="ion:cart-outline" />
            <span>Cart</span>
          </div>

          <!-- Sign In -->
          <div
            class="flex items-center gap-2 text-white hover:text-zinc-400"
            @click="Handlesignin"
          >
            <Icon icon="bi:person" />
            <span>Sign in</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Header from "../scripts/Header";
export default {
  ...Header,
};
</script>
<style>
/* WebKit (Chrome, Safari, Edge) */
*::-webkit-scrollbar {
  width: 0;
}

*::-webkit-scrollbar-track {
  background: transparent;
}

*::-webkit-scrollbar-thumb {
  background-color: transparent;
  border-radius: 14px;
  border: 3px solid var(--primary);
}
</style>

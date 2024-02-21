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
        v-if="user"
        class="flex items-center gap-2 text-white hover:text-zinc-400"
        @click="showLogin = true"
      >
        <div v-if="userLogin.length === 0" class="flex gap-2">
          <Icon icon="bi:person" class="text-lg" />
          <span>Sign in</span>
        </div>
      </div>
      <!-- login modal -->
      <LoginModal
        :is-visible="showLogin"
        @update:isVisible="showLogin = $event"
        @login-completed="HandleSignIn"
      ></LoginModal>
      <!-- login modal -->

      <!-- Cart -->
      <div
        :class="
          userLogin.length === 0 ? 'text-slate-200 pointer-events-none' : ''
        "
        @click="showCartFunction"
        class="flex items-center gap-2 text-white hover:text-zinc-400 relative"
      >
        <div v-if="cartItemsValue.length !== 0">
          <Icon
            icon="radix-icons:dot-filled"
            class="text-xl text-red-500 absolute -top-2 left-0"
          />
        </div>
        <Icon icon="ion:cart-outline" class="text-lg" />
        <span>Cart</span>
      </div>
      <div
        class="flex gap-2 justify-start items-center relative"
        v-if="userLogin.length !== 0"
      >
        <button
          @click="showCustomerSettings"
          class="flex gap-2 justify-start items-center"
        >
          <div class="h-10 w-10 bg-slate-100 rounded-full"></div>
          <div>
            <h1 class="text-white font-bold">{{ userLogin.username }}</h1>
          </div>
        </button>

        <div v-if="showSettings" class="absolute show top-11 z-50">
          <div
            class="bg-slate-700 text-slate-100 p-2 rounded-md shadow-xl shadow-slate-800/50 font-semibold"
          >
            <button
              @click="Logout()"
              class="py-2 px-4 w-full bg-slate-900/50 hover:bg-slate-900/75 rounded-md my-1"
            >
              Logout</button
            ><button
              class="py-2 px-4 w-full bg-slate-900/50 hover:bg-slate-900/75 rounded-md"
            >
              Settings
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- cart modal -->
    <div v-if="showCart" class="flex justify-center items-center">
      <div class="absolute top-32 right-3 z-30">
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
            <div class="flex gap-2 font-semibold mb-2">
              <input type="checkbox" @change="checkAll" />Select All
            </div>
            <div v-for="items in cartItemsValue" :key="items">
              <div class="my-1 relative">
                <div class="flex justify-start items-center gap-2">
                  <input
                    type="checkbox"
                    :checked="isChecked(items.product_id)"
                    @change="toggleCheckbox(items.product_id)"
                  />

                  <button
                    @click="deleteCartItems(items.product_id)"
                    class="flex my-1 absolute top-0 right-0 text-red-500 p-1 rounded-full bg-slate-400/75 shadow-sm"
                  >
                    <Icon icon="ic:round-delete" class="text-lg text-red-500" />
                  </button>

                  <img
                    :src="'data:image/png;base64,' + items.image"
                    alt=""
                    class="w-16 h-16 rounded-md"
                  />
                  <div>
                    <h1 class="font-bold text-xs">
                      {{ items.product_name }}
                    </h1>
                    <p class="text-xs font-semibold">{{ items.price }}</p>

                    <div class="flex gap-2">
                      <div
                        class="flex justify-center items-center gap-2 font-semibold text-slate-800"
                      >
                        <p>Qtty:</p>
                        <p
                          class="p-0.5 flex justify-center items-center w-7 rounded-md border-blue-500/50 border"
                        >
                          {{ items.quantity }}
                        </p>
                      </div>
                      <button
                        @click="decrement(items.product_id)"
                        :disabled="items.quantity === 1"
                        class="p-0.5 flex justify-center items-center w-7 rounded-full border"
                      >
                        <Icon icon="tabler:minus" />
                      </button>
                      <button
                        @click="increment(items.product_id)"
                        :disabled="items.quantity === 3"
                        class="p-0.5 flex justify-center items-center w-7 rounded-full border"
                      >
                        <Icon icon="mingcute:add-line" />
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <hr />
            </div>
            <!--  -->
            <div
              v-if="atLeastOneItemChecked || allItemsChecked"
              class="gap-2 my-2 shadow justify-center w-full items-center"
            >
              <button
                @click="checkout"
                class="flex justify-center w-full items-center gap-2 bg-blue-500 text-gray-100 p-2 rounded-md"
              >
                <Icon
                  icon="ic:outline-shopping-cart-checkout"
                  class="text-lg"
                />Checkout
              </button>
            </div>
            <!--  -->
          </div>
        </div>
      </div>
    </div>
    <!-- payment popup -->
    <div
      v-if="showPayment"
      class="justify-center items-center flex w-full h-full"
    >
      <div
        @click="closePayment()"
        class="fixed z-40 w-full top-0 left-0 h-full backdrop-blur bg-slate-900 bg-opacity-50"
      ></div>
      <div
        class="fixed z-50 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 justify-center items-center flex"
      >
        <div class="justify-center items-center flex">
          <div class="p-5 bg-slate-100 rounded-md h-96 w-96">
            <h1 class="font-semibold text-lg">Checkout</h1>
            <div class="bg-slate-200 rounded-md p-2 my-1">
              <span class="text-slate-900 text-sm">Delivery Address</span>
              <div class="text-xs text-slate-800">
                <p>R-jay | (+63) 091234567</p>
                <p>Door 6, Diko makita street</p>
                <p>South Philippines, 3000</p>
              </div>
            </div>
            <div class="bg-slate-200 rounded-md p-2">
              <span class="text-slate-900 text-sm font-medium"
                >R-jay Store</span
              >
              <div class="my-1">
                <div class="flex gap-2 justify-start items-center">
                  <div class="w-11 h-11 bg-slate-800 rounded-sm"></div>
                  <div>
                    <span class="text-sm font-semibold"
                      >Monitor 140hz Amoled Display</span
                    >
                    <p class="text-xs">$1000</p>
                    <p class="text-xs">x1</p>
                  </div>
                </div>
              </div>
              <div class="my-2">
                <div
                  class="flex gap-2 justify-between items-center border-y p-2 border-cyan-500/50 bg-cyan-300/10"
                >
                  <span class="text-sm font-medium">Shipping Fee</span>
                  <p class="text-xs">$10</p>
                </div>
              </div>
            </div>
            <div class="my-1">
              <div
                class="flex gap-2 justify-between items-center p-2 bg-blue-300/10"
              >
                <span class="text-xs">Order Total <span>(1)</span> Item</span>
                <p class="text-sm font-medium text-red-500">$1000</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--  -->
    <!-- cart modal -->

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

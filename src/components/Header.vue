<template>
  <!-- Top -->
  <div
    class="sm:flex justify-between py-4 px-2 sm:px-12 text-xs sm:text-sm cursor-pointer hidden"
  >
    <div class="flex place-items-center">
      <p class="text-slate-600">
        Need help? Call us:
        <span class="text-blue-500 text-base"> 09123456789</span>
      </p>
    </div>
    <div
      class="flex items-center gap-2 px-3 py-2 bg-blue-400/10 rounded-full shadow text-blue-500 hover:font-semibold transition"
      @click="orderTracking()"
      :class="userLogin.length === 0 ? 'text-blue-500 pointer-events-none' : ''"
    >
      <Icon icon="fluent:vehicle-bus-24-regular" class="text-lg" />
      <span>Track your order</span>
    </div>
  </div>

  <!-- payment popup -->
  <div
    v-if="showOrderTracking"
    class="justify-center items-center flex w-full h-full overflow-scroll"
  >
    <div
      @click="closeOrderTracking()"
      class="fixed z-40 w-full top-0 left-0 h-full backdrop-blur bg-slate-900 bg-opacity-50"
    ></div>
    <div
      class="fixed z-50 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 justify-center items-center flex"
    >
      <div class="overflow-scroll bg-slate-100 h-[500px] rounded-md">
        <div class="p-5 bg-slate-100 rounded-md h-full w-96 text-slate-800">
          <div class="h-full">
            <h1 class="font-semibold text-lg">Order Tracking</h1>
            <hr class="my-2" />
            <div v-if="orderData.length !== 0">
              <h1 class="text-base font-semibold py-1">
                Your Orders (<span class="text-blue-500">{{
                  orderData.length
                }}</span
                >)
              </h1>
              <hr class="my-2" />
              <div
                class="p-2 rounded-md bg-slate-400/10 my-2"
                v-for="(items, index) in orderData"
                :key="index"
              >
                <div>
                  <div>
                    <span class="font-semibold text-slate-800"
                      >#{{ items.order_number }}</span
                    >
                  </div>
                  <div class="my-2 flex gap-2 justify-start items-center">
                    <div>
                      <img
                        :src="'data:image/png;base64,' + items.image"
                        :alt="items.imageAlt"
                        class="h-20 w-20 object-center"
                      />
                    </div>
                    <div>
                      <h1 class="text-base font-semibold">
                        {{ items.product_name }}
                      </h1>
                      <p class="font-semibold">
                        Total:
                        <span
                          class="text-red-500 py-1 px-2 bg-slate-500/10 rounded-md"
                          >${{ items.total_price_products }}</span
                        >
                      </p>
                    </div>
                  </div>
                  <div>
                    <div v-if="items.status === 5" class="my-4">
                      <span
                        class="text-red-500 text-sm bg-red-400/10 p-2 rounded"
                        >Order Cancelled.</span
                      >
                    </div>
                    <div v-if="items.status !== 5">
                      <div
                        class="mb-5 flex justify-between items-center bg-blue-300/20 rounded-md p-1"
                      >
                        <div>
                          <p class="text-xs font-light">Date purchased</p>
                          <p class="text-sm font-semibold">
                            {{ items.date_purchased }}
                          </p>
                        </div>
                        <Icon
                          icon="iconamoon:arrow-right-2-light"
                          class="text-xl"
                        />
                        <div>
                          <p class="text-xs font-light">Estemated Delivery</p>
                          <p class="text-sm font-semibold">
                            {{ items.estimated_delivery }}
                          </p>
                        </div>
                      </div>
                      <ol
                        class="flex items-center w-full text-sm font-medium text-center text-gray-500 sm:text-base"
                      >
                        <li
                          class="flex md:w-full items-center sm:after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-2 xl:after:mx-2 dark:after:border-gray-600"
                        >
                          <span
                            class="flex text-xs items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200"
                          >
                            <div v-if="items.status >= 1">
                              <Icon
                                icon="lets-icons:check-fill"
                                class="text-lg text-blue-600"
                              />
                            </div>
                            Pending
                          </span>
                        </li>
                        <li
                          class="flex md:w-full items-center after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-2 xl:after:mx-2 dark:after:border-gray-600"
                        >
                          <span
                            class="flex text-xs items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200"
                          >
                            <div v-if="items.status >= 2">
                              <Icon
                                icon="lets-icons:check-fill"
                                class="text-lg text-blue-600"
                              />
                            </div>
                            Processing
                          </span>
                        </li>
                        <li
                          class="flex md:w-full items-center after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-2 xl:after:mx-2 dark:after:border-gray-600"
                        >
                          <span
                            class="flex text-xs items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200"
                          >
                            <div v-if="items.status >= 3">
                              <Icon
                                icon="lets-icons:check-fill"
                                class="text-lg text-blue-600"
                              />
                            </div>
                            Out for delivery
                          </span>
                        </li>
                        <li class="flex items-center text-xs">
                          <div v-if="items.status >= 4">
                            <Icon
                              icon="lets-icons:check-fill"
                              class="text-lg text-blue-600"
                            />
                          </div>
                          Delivered
                        </li>
                      </ol>
                      <div
                        class="border border-blue-500/50 rounded-md p-2 my-5"
                      >
                        <div
                          v-if="items.status >= 1"
                          class="bg-blue-500/10 rounded-md p-1"
                        >
                          <p class="text-xs font-light">
                            {{ items.created_at }}
                          </p>
                          <p class="text-sm font-light text-blue-600">
                            Thank you for your order! It is currently pending.
                            We're getting it ready to be processed. Stay tuned
                            for more updates.
                          </p>
                        </div>
                        <div
                          v-if="items.status >= 2"
                          class="bg-blue-500/10 rounded-md p-1 my-1"
                        >
                          <p class="text-xs font-light">
                            {{ items.processing_date }}
                          </p>
                          <p class="text-sm font-light text-blue-600">
                            Your order is currently being processed. We'll
                            update you once it's on its way!
                          </p>
                        </div>
                        <div
                          v-if="items.status >= 3"
                          class="bg-blue-500/10 rounded-md p-1 my-1"
                        >
                          <p class="text-xs font-light">
                            {{ items.delivery_date }}
                          </p>
                          <p class="text-sm font-light text-blue-600">
                            Great news! Your order is out for delivery. It'll be
                            with you soon.
                          </p>
                        </div>
                        <div
                          v-if="items.status >= 4"
                          class="bg-blue-500/10 rounded-md p-1 my-1"
                        >
                          <p class="text-xs font-light">
                            {{ items.delivered_date }}
                          </p>
                          <p class="text-sm font-light text-blue-600">
                            Your order has been delivered. We hope you enjoy
                            your purchase!
                          </p>
                        </div>
                      </div>
                      <!-- if review sent -->
                      <div v-if="items.comment !== null">
                        <p
                          class="text-base font-semibold text-gray-500 px-5 py-2 bg-gray-300 rounded border my-2"
                        >
                          Review Sent
                        </p>
                      </div>
                      <!-- ratings and comment here -->
                      <div
                        v-if="items.status === 4 && items.comment === null"
                        class="rating-and-comment"
                      >
                        <div class="ratings flex justify-center">
                          <button
                            v-for="number in 5"
                            :key="`rating-${number}-${index}`"
                            @click="items.userRating = number"
                            :class="{ active: items.userRating >= number }"
                          >
                            <span v-if="items.userRating >= number">★</span>
                            <span v-else>☆</span>
                          </button>
                        </div>
                        <textarea
                          v-model="items.userComment"
                          placeholder="Leave a comment"
                          class="my-4 p-2 rounded"
                        ></textarea>
                        <button
                          class="px-3 py-1 bg-blue-500 text-white rounded"
                          @click="submitComment(items, index)"
                        >
                          Submit
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="orderData.length === 0">
              <h1
                class="text-base font-semibold text-red-500 px-5 py-2 bg-red-300/10 rounded-full border my-2"
              >
                You have no orders
              </h1>
            </div>

            <hr class="border" />
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Navigator -->
  <div
    class="text-xs sm:text-sm flex container-fluid shadow-lg bg-gradient-to-r from-blue-600/75 via-violet-500/50 to-orange-500/10 p-2 sm:p-4 pl-2 sm:pl-12 place-items-center cursor-pointer"
  >
    <!-- kulang ng logo dito -->
    <div class="flex sm:ml-10 ml-0">
      <img src="@/assets/logo.jpg" alt="" class="w-12 h-12 rounded-full mr-2" />
    </div>
    <!-- Search bar -->
    <div class="sm:ml-5">
      <div
        class="flex justify-between bg-none lg:bg-slate-100 border shadow-lg rounded-full overflow-hidden"
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
    <div class="hidden sm:flex ml-auto items-center sm:mr-12 cursor-pointer">
      <!-- sign in -->
      <div
        v-if="user"
        class="flex items-center gap-2 rounded-full w-full text-white"
        @click="showLogin = true"
      >
        <div
          v-if="userLogin.length === 0"
          class="flex gap-1 bg-blue-600 shadow-xl hover:bg-blue-500 py-2 px-3 rounded-full"
        >
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
          userLogin.length === 0 ? 'text-slate-800  pointer-events-none' : ''
        "
        @click="showCartFunction"
        class="flex items-center gap-2 text-slate-800 relative hover:bg-slate-500/20 rounded-full py-1 px-2"
      >
        <div v-if="cartItemsValue.length !== 0">
          <Icon
            icon="radix-icons:dot-filled"
            class="text-xl text-red-500 absolute -top-2 left-0"
          />
        </div>
        <Icon icon="ion:cart-outline" class="text-xl" />
        <p class="text-base">Cart</p>
      </div>
      <div
        class="flex gap-2 justify-start items-center relative"
        v-if="userLogin.length !== 0"
      >
        <button
          @click="showCustomerSettings"
          class="flex gap-2 justify-start mx-5 items-center"
        >
          <img
            src="@/assets/profile.jpg"
            alt=""
            class="w-10 h-10 rounded-full mr-2"
          />

          <div>
            <h1 class="text-slate-800 truncate font-bold">
              {{ userLogin.username }}
            </h1>
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
            v-if="cartItemsValue.length !== 0"
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
                    @click="deleteCartItems(items.cart_item_id)"
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
          <div v-if="cartItemsValue.length === 0">
            <p class="px-5 py-2 text-red-500 bg-red-400/10 rounded-full shadow">
              No items
            </p>
          </div>
        </div>
      </div>
    </div>
    <!-- payment popup -->
    <div
      v-if="showPayment"
      class="justify-center items-center flex w-full h-full overflow-scroll"
    >
      <div
        @click="closePayment()"
        class="fixed z-40 w-full top-0 left-0 h-full backdrop-blur bg-slate-900 bg-opacity-50"
      ></div>
      <div
        class="fixed z-50 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 justify-center items-center flex"
      >
        <div class="overflow-scroll bg-slate-100 h-[500px] rounded-md">
          <div class="p-5 bg-slate-100 rounded-md h-full w-96">
            <div class="h-full">
              <h1 class="font-semibold text-lg">Checkout</h1>
              <div class="bg-slate-200 rounded-md p-2 my-1">
                <span class="text-slate-900 text-sm">Customer Information</span>
                <div v-if="userLogin" class="text-xs text-slate-800">
                  <p>Name: {{ userLogin.username }}</p>
                  <p>Contact No: {{ userLogin.contact_number }}</p>
                  <p>Address: {{ userLogin.address }}</p>
                </div>
              </div>
              <div class="bg-slate-200 rounded-md p-2">
                <div
                  class="my-1"
                  v-for="(items, index) in itemsToCheckout"
                  :key="index"
                >
                  <hr class="border my-2 border-gray-700/10" />
                  <div v-for="product in items" :key="product">
                    <div class="flex gap-2 justify-start items-center">
                      <div>
                        <img
                          :src="'data:image/png;base64,' + product.image"
                          alt=""
                          class="w-10 h-10 rounded-md"
                        />
                      </div>
                      <div>
                        <span class="text-sm font-semibold">{{
                          product.product_name
                        }}</span>
                        <p class="text-xs">{{ product.price }}</p>
                        <p class="text-xs">x{{ product.quantity }}</p>
                      </div>
                    </div>
                    <div class="my-2">
                      <div
                        class="flex gap-2 justify-between items-center border-y p-2 border-cyan-500/50 bg-cyan-300/10"
                      >
                        <span class="text-sm font-medium">Shipping Fee</span>
                        <p class="text-xs">{{ product.shipping_fee }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="my-1">
                <div
                  class="flex gap-2 justify-between items-center p-2 rounded-md bg-blue-300/10"
                >
                  <span class="text-xs"
                    >Order Total
                    <span>({{ itemsToCheckout.length }})</span> Item</span
                  >
                  <p class="text-sm font-medium text-red-500">
                    ₱{{ priceTotalAll }}
                  </p>
                </div>
              </div>
              <div class="my-1">
                <div
                  class="flex gap-2 justify-between items-center p-2 rounded-md bg-blue-500/10"
                >
                  <div class="w-full">
                    <span class="text-sm font-semibold"> Payment Option</span>
                    <div class="block w-full my-2">
                      <button
                        @click="onDelivery"
                        :class="{
                          ' shadow-md shadow-green-500/50 border-green-500':
                            selectedPayment === 'cash on delivery',
                        }"
                        class="p-2 bg-green-500/10 flex justify-center items-center gap-1 rounded-full my-1 border border-gray-600/50 w-full text-green-600 font-medium text-sm"
                      >
                        <Icon icon="iconoir:cash-solid" class="text-lg" /> Cash
                        on Delivery
                      </button>
                      <button
                        @click="onPyment"
                        :class="{
                          ' shadow-md shadow-blue-500/50 border-blue-500':
                            selectedPayment === 'payment',
                        }"
                        class="p-2 bg-green-blue/10 rounded-full flex justify-center items-center gap-1 my-1 border border-gray-600/50 w-full text-blue-600 font-medium text-sm"
                      >
                        <Icon icon="material-symbols:wallet" class="text-lg" />
                        Payment Center/E-wallet
                      </button>
                      <button
                        @click="onCredit"
                        :class="{
                          ' shadow-md shadow-orange-500/50 border-orange-500':
                            selectedPayment === 'credit',
                        }"
                        class="p-2 bg-green-orange/10 rounded-full flex justify-center items-center gap-1 my-1 border border-gray-600/50 w-full text-orange-600 font-medium text-sm"
                      >
                        <Icon
                          icon="material-symbols:credit-card"
                          class="text-lg"
                        />
                        Credit Card
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="my-3">
                <button
                  :disabled="selectedPayment === ''"
                  :class="{
                    ' cursor-not-allowed opacity-75': selectedPayment === '',
                  }"
                  @click="submitOrder"
                  class="w-full rounded-full bg-blue-500 p-2 text-slate-100 text-lg font-semibold shadow-md shadow-blue-500/50"
                >
                  Place Order
                </button>
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
      class="sm:hidden fixed w-72 inset-y-0 right-0 bg-sky-800 z-50 transition-transform duration-300 ease-in-out"
    >
      <div class="flex flex-col items-end p-4">
        <!-- Close Button -->
        <div
          class="cursor-pointer text-red-400 bg-red-500/20 rounded-full p-2"
          @click="toggleSidebar"
        >
          <Icon icon="heroicons-solid:x" class="text-2xl" />
        </div>
      </div>
      <div class="px-4">
        <!-- Sidebar Content -->
        <div class="flex flex-col items-start">
          <!-- track your order -->
          <div
            class="flex items-center gap-2 p-2 rounded-md hover:bg-slate-800/50 w-full text-white hover:font-bold"
          >
            <Icon icon="fluent:vehicle-bus-24-regular" class="text-xl" />
            <h1 class="text-base font-medium">Track your order</h1>
          </div>

          <!-- Cart -->
          <div
            @click="showCartFunction"
            class="flex items-center gap-2 p-2 rounded-md hover:bg-slate-800/50 w-full text-white hover:font-bold"
          >
            <Icon icon="ion:cart-outline" class="text-xl" />
            <h1 class="text-base font-medium">Cart</h1>
          </div>

          <!-- Sign In -->
          <div
            class="flex items-center gap-2 p-2 rounded-md hover:bg-slate-800/50 w-full text-white hover:font-bold"
            @click="Handlesignin"
          >
            <Icon icon="bi:person" class="text-xl" />
            <h1 class="text-base font-medium">Sign in</h1>
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
<style scoped>
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
.ratings button {
  margin: 0 5px;
  background-color: transparent;
  border: none;
  cursor: pointer;
  font-size: 24px;
}

.ratings button.active {
  color: #ffcc00; /* Gold color for active stars */
}

textarea {
  display: block;
  margin-top: 20px;
  width: 100%;
}
</style>

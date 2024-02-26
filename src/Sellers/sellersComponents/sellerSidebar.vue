<template>
  <div class="bg-gray-100/10 shadow h-screen w-60">
    <div class="h-full">
      <div class="py-3 px-3 font-semibold text-gray-700"><h1>Logo</h1></div>
      <div class="px-2">
        <hr />
        <h1 class="font-semibold text-gray-500 py-3">Main Menu</h1>
        <hr />
        <div>
          <RouterLink to="/seller_products">
            <div
              :class="{
                ' text-slate-700 bg-sky-500/25 shadow font-medium ':
                  $route.name === 'seller_products',
                'text-slate-500 bg-sky-100/10 font-normal':
                  $route.name !== 'seller_products',
                ' font-semibold text-base': true,
              }"
              class="px-2 py-1 my-2 flex gap-2 justify-start rounded-md items-center"
            >
              <Icon icon="akar-icons:home" class="text-xl" />
              Dashboard
            </div>
          </RouterLink>
          <RouterLink to="/seller_order_management">
            <div
              :class="{
                ' text-slate-700 bg-sky-500/25 shadow font-medium':
                  $route.name === 'seller_order_management' ||
                  $route.name === 'seller_order_management_pending',
                'text-slate-500 bg-sky-100/10 font-normal':
                  $route.name !== 'seller_order_management',
                ' font-semibold text-base': true,
              }"
              class="px-2 py-1 my-2 flex gap-2 justify-start rounded-md items-center"
            >
              <Icon icon="bi:cart" class="text-xl" />
              Order Management
            </div>
          </RouterLink>
          <RouterLink to="/seller_customers">
            <div
              :class="{
                ' text-slate-700 bg-sky-500/25 shadow font-medium':
                  $route.name === 'seller_customers',
                'text-slate-500 bg-sky-100/10 font-normal':
                  $route.name !== 'seller_customers',
                ' font-semibold text-base': true,
              }"
              class="px-2 py-1 my-2 flex gap-2 justify-start rounded-md items-center"
            >
              <Icon icon="bi:people" class="text-xl" />
              Customers
            </div>
          </RouterLink>
          <RouterLink to="/seller_transations">
            <div
              :class="{
                ' text-slate-700 bg-sky-500/25 shadow font-medium':
                  $route.name === 'seller_transations',
                'text-slate-500 bg-sky-100/10 font-normal':
                  $route.name !== 'seller_transations',
                ' font-semibold text-base': true,
              }"
              class="px-2 py-1 my-2 flex gap-2 justify-start rounded-md items-center"
            >
              <Icon icon="bitcoin-icons:transactions-filled" class="text-xl" />
              Transactions
            </div>
          </RouterLink>
          <div>
            <hr />
            <h1 class="font-semibold text-gray-500 py-3">Products</h1>
            <hr />
          </div>
          <RouterLink to="/seller_Add_products">
            <div
              :class="{
                ' text-slate-700 bg-sky-500/25 shadow font-medium':
                  $route.name === 'seller_Add_products',
                'text-slate-500 bg-sky-100/10 font-normal':
                  $route.name !== 'seller_Add_products',
                ' font-semibold text-base': true,
              }"
              class="px-2 py-1 my-2 flex gap-2 justify-start rounded-md items-center"
            >
              <Icon icon="gala:add" class="text-xl" />

              Add Products
            </div>
          </RouterLink>
          <RouterLink to="/seller_product_list">
            <div
              :class="{
                ' text-slate-700 bg-sky-500/25 shadow font-medium':
                  $route.name === 'seller_product_list',
                'text-slate-500 bg-sky-100/10 font-normal':
                  $route.name !== 'seller_product_list',
                ' font-semibold text-base': true,
              }"
              class="px-2 py-1 my-2 flex gap-2 justify-start rounded-md items-center"
            >
              <Icon icon="cil:list" class="text-xl" />
              Product List
            </div>
          </RouterLink>
        </div>
      </div>
    </div>
    <div class="relative w-full my-2">
      <div class="absolute bottom-0 bg-slate-50 w-full z-10 px-2">
        <hr />
        <h1 class="font-semibold text-gray-500 py-3">Settings</h1>
        <hr />
        <div class="my-1">
          <button
            @click="logout"
            class="flex gap-3 justify-start items-center font-semibold hover:bg-slate-400/20 rounded-md text-slate-700 w-full py-2"
          >
            <div class="bg-blue-500/20 shadow-sm rounded-full">
              <Icon icon="typcn:user" class="text-3xl text-blue-500" />
            </div>
            {{ userLogin.store_name }}
          </button>
        </div>

        <div class="mt-1 mb-3">
          <button
            @click="logout"
            class="flex justify-start items-center font-semibold hover:bg-slate-400/20 rounded-md text-slate-700 w-full py-2"
          >
            <Icon icon="solar:logout-line-duotone" class="text-2xl" />
            Logout
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { ref } from "vue";
import { Icon } from "@iconify/vue";
export default {
  components: {
    Icon,
  },
  setup() {
    const logout = () => {
      confirm("logout?");
    };
    var userLogin = ref([]);
    const getUserFromLocalStorage = () => {
      const userData = localStorage.getItem("seller");
      if (userData) {
        userLogin.value = JSON.parse(userData);
      }
      return null;
    };
    getUserFromLocalStorage();
    return {
      logout,
      userLogin,
    };
  },
};
</script>

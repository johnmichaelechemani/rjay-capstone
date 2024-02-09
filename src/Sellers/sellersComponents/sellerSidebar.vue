<template>
  <div class="bg-gray-100/10 shadow h-screen w-60">
    <div class="py-3 px-3 font-semibold text-gray-700"><h1>Logo</h1></div>
    <div class="px-2">
      <hr />
      <h1 class="font-semibold text-gray-500 py-3">Main Menu</h1>
      <div>
        <RouterLink to="/seller_products">
          <div
            :class="{
              ' text-slate-700 bg-sky-100/25 shadow font-medium ':
                $route.name === 'seller_products',
              'text-slate-500 bg-sky-100/10': $route.name !== 'seller_products',
              ' font-semibold text-base': true,
            }"
            class="px-2 py-1 my-2 flex gap-1 justify-start rounded-md items-center"
          >
            <Icon icon="akar-icons:home" class="text-xl" />
            Dashboard
          </div>
        </RouterLink>
        <RouterLink to="/">
          <div
            :class="{
              ' text-slate-700 bg-sky-100/25 shadow font-medium':
                $route.name === '',
              'text-slate-500 bg-sky-100/10': $route.name !== '',
              ' font-semibold text-base': true,
            }"
            class="px-2 py-1 my-2 flex gap-1 justify-start rounded-md items-center"
          >
            <Icon icon="bi:cart" class="text-xl" />
            Order Management
          </div>
        </RouterLink>
        <RouterLink to="/">
          <div
            :class="{
              ' text-slate-700 bg-sky-100/25 shadow font-medium':
                $route.name === '',
              'text-slate-500 bg-sky-100/10': $route.name !== '',
              ' font-semibold text-base': true,
            }"
            class="px-2 py-1 my-2 flex gap-1 justify-start rounded-md items-center"
          >
            <Icon icon="bi:people" class="text-xl" />
            Customers
          </div>
        </RouterLink>
        <RouterLink to="/">
          <div
            :class="{
              ' text-slate-700 bg-sky-100/25 shadow font-medium':
                $route.name === '',
              'text-slate-500 bg-sky-100/10': $route.name !== '',
              ' font-semibold text-base': true,
            }"
            class="px-2 py-1 my-2 flex gap-1 justify-start rounded-md items-center"
          >
            <Icon icon="bitcoin-icons:transactions-filled" class="text-xl" />
            Transactions
          </div>
        </RouterLink>
        <RouterLink to="/seller_Add_products">
          <div
            :class="{
              ' text-slate-700 bg-sky-100/25 shadow font-medium':
                $route.name === 'seller_Add_products',
              'text-slate-500 bg-sky-100/10':
                $route.name !== 'seller_Add_products',
              ' font-semibold text-base': true,
            }"
            class="px-2 py-1 my-2 flex gap-1 justify-start rounded-md items-center"
          >
            <Icon icon="gala:add" class="text-xl" />

            Add Products
          </div>
        </RouterLink>
        <RouterLink to="/">
          <div
            :class="{
              ' text-slate-700 bg-sky-100/25 shadow font-medium':
                $route.name === '',
              'text-slate-500 bg-sky-100/10': $route.name !== '',
              ' font-semibold text-base': true,
            }"
            class="px-2 py-1 my-2 flex gap-1 justify-start rounded-md items-center"
          >
            <Icon icon="cil:list" class="text-xl" />
            Product List
          </div>
        </RouterLink>
      </div>
    </div>
  </div>
</template>
<script>
import { Icon } from "@iconify/vue";
export default {
  components: {
    Icon,
  },
};
</script>

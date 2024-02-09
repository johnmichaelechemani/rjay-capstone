<template>
  <div class="mx-4">
    <div class="py-3 px-10 font-bold text-2xl text-slate-700">
      <h1>Order Management</h1>
    </div>
    <div class="flex mt-5">
      <RouterLink to="/seller_order_management_pending">
        <div
          :class="{
            ' text-sky-500 font-medium':
              $route.name === 'seller_order_management_pending',
            'text-slate-500 font-normal':
              $route.name !== 'seller_order_management_pending',
            '': true,
          }"
        >
          <h1 class="px-5 text-lg">Pending</h1>
          <hr
            v-if="$route.name === 'seller_order_management_pending'"
            :class="{
              ' mt-1 border border-sky-500 rounded-full':
                $route.name === 'seller_order_management_pending',
            }"
          />
        </div>
      </RouterLink>
      <RouterLink to="/seller_order_management_confirmed">
        <div
          :class="{
            ' text-sky-500 font-medium':
              $route.name === 'seller_order_management_confirmed',
            'text-slate-500 font-normal':
              $route.name !== 'seller_order_management_confirmed',
            '': true,
          }"
        >
          <h1 class="px-5 text-lg">Confirmed</h1>
          <hr
            v-if="$route.name === 'seller_order_management_confirmed'"
            :class="{
              ' mt-1 border border-sky-500 rounded-full':
                $route.name === 'seller_order_management_confirmed',
            }"
          />
        </div>
      </RouterLink>
    </div>
    <hr />
    <div class="my-5">
      <router-view />
    </div>
  </div>
</template>

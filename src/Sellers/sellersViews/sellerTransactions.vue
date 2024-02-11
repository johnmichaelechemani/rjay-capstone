<template>
  <div class="mx-4">
    <div class="py-3 px-10 font-bold text-2xl text-slate-700">
      <h1>Transactions</h1>
    </div>

    <div class="flex justify-between items-center mt-5">
      <div class="p-1 border rounded-lg">
        <div class="flex gap-2">
          <h1
            class="font-semibold text-blue-500 text-sm bg-blue-400/10 p-2 rounded-md"
          >
            All Status
          </h1>
          <h1 class="font-semibold text-slate-600 text-sm p-2 rounded-md">
            Processing
          </h1>
          <h1 class="font-semibold text-slate-600 text-sm p-2 rounded-md">
            Shiped
          </h1>
          <h1 class="font-semibold text-slate-600 text-sm p-2 rounded-md">
            Delivered
          </h1>
          <h1 class="font-semibold text-slate-600 text-sm p-2 rounded-md">
            Cancelled
          </h1>
        </div>
      </div>
      <div class="my-3">
        <div
          class="border rounded-full w-60 shadow flex justify-between items-center px-4"
        >
          <input
            type="text"
            placeholder="Search Stock or Orders"
            class="outline-none placeholder:text-sm placeholder:font-light py-2 pl-2 w-full rounded-full"
          />
          <Icon icon="carbon:search" class="text-xl text-slate-500 my-2 ml-2" />
        </div>
      </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-10">
      <div class="border p-3 rounded-lg">
        <div class="flex justify-between items-center mb-2">
          <p class="text-xs font-medium text-blue-700">1234</p>
          <span class="p-2 rounded-lg bg-slate-600/10 text-xs font-medium"
            >Cancelled</span
          >
        </div>
        <hr />
        <div class="flex justify-between gap-5 items-center mb-2 my-2">
          <div class="flex gap-2 justify-start items-center">
            <div class="h-11 w-11 bg-slate-600/20 rounded-md"></div>
            <div>
              <p class="text-lg text-slate-700 font-semibold">Monitor</p>
              <span class="text-xs text-slate-600"
                >1x <span>$ 128.00</span></span
              >
            </div>
          </div>
          <div>
            <span class="text-lg font-semibold text-slate-700">$183.00</span>
          </div>
        </div>
        <div class="w-full">
          <div class="flex justify-start gap-10 items-start">
            <div>
              <p class="text-xs font-semibold text-slate-500">Order Date</p>
              <p class="text-sm font-semibold text-slate-700 py-2">
                28 September 2024
              </p>
            </div>
            <div>
              <p class="text-xs font-semibold text-slate-500">Customer</p>
              <p class="text-sm font-semibold text-slate-700 py-2">R-jay</p>
            </div>
            <div>
              <p class="text-xs font-semibold text-slate-500">Payment</p>
              <p class="text-sm font-semibold text-slate-700 py-2">Paypal</p>
            </div>
          </div>
        </div>
      </div>
      <div>dasdasdas</div>
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

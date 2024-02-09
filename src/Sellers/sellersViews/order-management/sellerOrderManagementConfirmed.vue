<template>
  <div>
    <div
      class="border rounded-full w-60 shadow flex justify-between items-center px-4"
    >
      <input
        type="text"
        placeholder="Search by order id"
        class="outline-none placeholder:text-sm placeholder:font-light py-2 pl-2 w-full rounded-full"
      />
      <Icon icon="carbon:search" class="text-xl text-slate-500 my-2 ml-2" />
    </div>
    <div class="my-5"><h1 class="font-semibold text-slate-700">Orders</h1></div>
    <div class="my-5">
      <div class="relative overflow-x-auto shadow rounded-md">
        <table
          class="w-full text-sm text-left rtl:text-right text-gray-900 rounded-md"
        >
          <thead
            class="text-xs text-slate-800 bg-slate-100/20 uppercase rounded-md"
          >
            <tr class="bg-gray-100/10 border-b border-gray-600/50">
              <th scope="col" class="px-6 py-3">Order Id</th>
              <th scope="col" class="px-6 py-3">Created</th>
              <th scope="col" class="px-6 py-3">Customer</th>
              <th scope="col" class="px-6 py-3">Total</th>
              <th scope="col" class="px-6 py-3">Profit</th>
              <th scope="col" class="px-6 py-3">Status</th>
            </tr>
          </thead>
          <tbody class="">
            <tr class="bg-gray-100/10 border-b border-gray-600/50">
              <th
                scope="row"
                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
              >
                1234
              </th>
              <td class="px-6 py-4">4 min ago</td>
              <td class="px-6 py-4">R-jay</td>
              <td class="px-6 py-4">$2999</td>
              <td class="px-6 py-4">$152</td>
              <td class="px-6 py-4">
                <p
                  class="bg-green-600/10 shadow px-3 py-1 rounded-full text-green-600"
                >
                  Confirmed
                </p>
              </td>
            </tr>
          </tbody>
        </table>
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

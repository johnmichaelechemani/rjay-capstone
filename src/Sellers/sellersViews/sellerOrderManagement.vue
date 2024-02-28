<template>
  <div class="mx-4">
    <div class="py-3 px-10 font-bold text-2xl text-slate-700">
      <h1>Order</h1>
    </div>
    <hr />
    <div class="flex mt-5 w-full">
      <div>
        <div class="flex gap-3">
          <div
            class="border rounded-md w-60 shadow flex justify-between items-center px-4"
          >
            <input
              type="text"
              placeholder="Search by order id"
              class="outline-none placeholder:text-sm placeholder:font-light py-2 pl-2 w-full rounded-full"
            />
            <Icon
              icon="carbon:search"
              class="text-xl text-slate-500 my-2 ml-2"
            />
          </div>

          <form class="">
            <select
              id="status"
              class="shadow border text-gray-900 outline-none text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-32 px-3 py-2.5"
            >
              <option selected>Status</option>
              <option value="">Pending</option>
              <option value="">Cancelled</option>
              <option value="">Shipped</option>
              <option value="">New order</option>
            </select>
          </form>
        </div>

        <div class="my-5 w-full">
          <div class="relative w-full overflow-x-auto shadow rounded-md">
            <table
              class="w-full text-sm text-left rtl:text-right text-gray-900 rounded-md"
            >
              <thead
                class="text-xs text-slate-800 bg-slate-100/20 uppercase rounded-md"
              >
                <tr class="bg-gray-100/10 border-b border-gray-600/50">
                  <th scope="col" class="px-6 py-3">Order Id</th>
                  <th scope="col" class="px-6 py-3">ORDER NUMBER</th>
                  <th scope="col" class="px-6 py-3">STATUS</th>
                  <th scope="col" class="px-6 py-3">ITEM</th>
                  <th scope="col" class="px-6 py-3">CUSTOMER NAME</th>
                  <th scope="col" class="px-6 py-3">SHIPPING SERVICE</th>
                  <th scope="col" class="px-6 py-3">TRACKING CODE</th>
                  <th scope="col" class="px-6 py-3"></th>
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
                  <td class="px-6 py-4">563421</td>
                  <td class="px-6 py-4">
                    <p
                      class="bg-orange-300/10 shadow px-3 py-1 rounded-full text-orange-600"
                    >
                      Pending
                    </p>
                  </td>
                  <td class="px-6 py-4">1</td>
                  <td class="px-6 py-4">R-jay</td>
                  <td class="px-6 py-4">
                    <p class="text-violet-600">Standard</p>
                  </td>
                  <td class="px-6 py-4">12345676890</td>
                  <td class="px-6 py-4">
                    <Icon icon="basil:edit-outline" class="text-lg" />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
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

<template>
  <div class="p-2 border border-slate-900/20 rounded-md">
    <div class="relative overflow-x-auto">
      <table
        class="w-full text-sm text-left rtl:text-right text-gray-900 rounded-md"
      >
        <thead class="text-xs text-sky-100 uppercase bg-sky-900 rounded-md">
          <tr>
            <th scope="col" class="px-6 py-3">Sellers Name</th>
            <th scope="col" class="px-6 py-3">Email</th>
            <th scope="col" class="px-6 py-3">Category</th>
            <th scope="col" class="px-6 py-3">Delete</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="user in seller"
            :key="user"
            class="bg-gray-200 border-b border-gray-700"
          >
            <th
              scope="row"
              class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
            >
              {{ user.seller_name }}
            </th>
            <td class="px-6 py-4">{{ user.phone_number }}</td>
            <td class="px-6 py-4">{{ user.category }}</td>
            <td class="px-6 py-4 flex justify-center">
              <button @click="deleteseller(user.seller_id)">
                <Icon
                  icon="material-symbols-light:delete-sharp"
                  class="text-2xl text-red-500"
                />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script>
import { Icon } from "@iconify/vue";
export default {
  components: {
    Icon,
  },
  setup() {
    const seller = [
      {
        seller_id: 1,
        seller_name: "John Doe",
        phone_number: "+380999999999",
        category: "Laptop PC",
      },
      {
        seller_id: 2,
        seller_name: "John",
        phone_number: "+380999999999",
        category: "Shafol PC",
      },
    ];
    const deleteseller = (seller_id) => {
      console.log("delete seller: ", seller_id);
    };
    return {
      deleteseller,
      seller,
    };
  },
};
</script>

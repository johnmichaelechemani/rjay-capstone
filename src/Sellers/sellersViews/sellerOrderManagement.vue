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
              <option value="">Out for Delivery</option>
              <option value="">Delivered</option>
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
                  <th scope="col" class="px-6 py-3">Order Number</th>
                  <th scope="col" class="px-6 py-3">Product name</th>
                  <th scope="col" class="px-6 py-3">STATUS</th>
                  <th scope="col" class="px-6 py-3">QUANTITY</th>
                  <th scope="col" class="px-6 py-3">CUSTOMER NAME</th>
                  <th scope="col" class="px-6 py-3">PRICE</th>
                  <th scope="col" class="px-6 py-3">PAYMENT METHOD</th>
                  <th scope="col" class="px-6 py-3">ORDER DATE</th>
                  <th scope="col" class="px-6 py-3">ESTIMATED DELIVERY</th>
                  <th scope="col" class="px-6 py-3">EDIT</th>
                </tr>
              </thead>
              <tbody class="">
                <tr
                  v-for="item in orders"
                  :key="item.id"
                  class="bg-gray-100/10 border-b border-gray-600/50"
                >
                  <th
                    scope="row"
                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                  >
                    {{ item.order_detail_id }}
                  </th>
                  <td class="px-6 py-4">{{ item.order_number }}</td>
                  <td class="px-6 py-4">{{ item.product_name }}</td>
                  <td class="px-6 py-4">
                    <p
                      class="shadow px-3 py-1 text-center rounded-full"
                      :class="{
                        'text-orange-500 bg-orange-300/10':
                          item.status === 'pending',
                        'text-blue-500 bg-blue-300/10':
                          item.status === 'out_for_delivery',
                        'text-green-500 bg-red-300/10':
                          item.status === 'delivered',
                      }"
                    >
                      {{ item.status }}
                    </p>
                  </td>
                  <td class="px-6 py-4">{{ item.quantity }}</td>
                  <td class="px-6 py-4">{{ item.username }}</td>
                  <td class="px-6 py-4">{{ item.total_price_products }}</td>
                  <td class="px-6 py-4">
                    <p class="text-violet-600">
                      {{ item.payment_method }}
                    </p>
                  </td>
                  <td class="px-6 py-4">
                    {{ item.created_at }}
                  </td>
                  <td class="px-6 py-4">
                    {{ item.estimated_delivery }}
                  </td>
                  <td class="px-6 py-4">
                    <button @click="editStatus(item.order_detail_id)">
                      <Icon
                        icon="material-symbols:edit"
                        class="text-lg text-green-500"
                      />
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- edit status modal -->
  <div
    v-if="showStatusModal"
    class="z-50 fixed top-0 left-0 h-full w-full flex justify-center items-center"
  >
    <form
      @submit.prevent="handleEditStatusOrder"
      class="w-72 bg-gray-200 p-5 rounded-md shadow"
    >
      <div>
        <p class="text-sm">Customer Information:</p>
        <p class="bg-gray-500/20 rounded-md my-2 p-2">
          Name :{{ editableOrderStatus.username }}
        </p>
        <p class="bg-gray-500/20 rounded-md my-2 p-2">
          Contact no: {{ editableOrderStatus.contact_number }}
        </p>
        <p class="bg-gray-500/20 rounded-md my-2 p-2">
          Address: {{ editableOrderStatus.address }}
        </p>
      </div>
      <div>
        <h1 class="text-lg font-semibold text-blue-400">
          Select order Status:
        </h1>
        <select v-model="selectValue" class="w-full p-2 rounded-md">
          <option value="pending">pending</option>
          <option value="out_for_delivery">Out for delivery</option>
          <option value="delivered">Delivered</option>
        </select>
      </div>
      <div v-if="selectValue === 'pending'">
        <h1 class="text-lg font-semibold text-blue-400 mt-4">
          Estimated Delivery Date:
        </h1>
        <input
          type="date"
          v-model="estimatedDelivery"
          class="w-full p-2 rounded-md border-gray-300 shadow-sm"
        />
      </div>
      <!-- Add more inputs as needed here -->
      <div class="flex justify-evenly my-5 gap-5 items-center">
        <button
          type="button"
          @click="closeEditStatusModal()"
          class="px-4 py-2 bg-gray-400/20 text-slate-700 w-full rounded-md shadow"
        >
          Cancel
        </button>
        <button
          type="submit"
          class="px-4 py-2 bg-green-400/20 text-green-700 hover:bg-green-500/25 w-full rounded-md shadow"
        >
          Update
        </button>
      </div>
    </form>
  </div>
</template>
<script>
// YourComponent.vue <script> part
import { ref, onMounted } from "vue";
import { Icon } from "@iconify/vue";
import axios from "axios";
import { userLogin, getUserFromLocalStorage } from "@/scripts/Seller"; // Adjust the path as necessary

export default {
  components: {
    Icon,
  },
  setup() {
    const refreshPage = () => {
      location.reload(true);
    };

    const orders = ref([]);

    let selectValue = ref("");

    let estimatedDelivery = ref("");

    const showStatusModal = ref(false);

    const editableOrderStatus = ref([]);

    const userOrderName = ref(""); // constaining  the name of the order that is being edited in status modal

    let orderIdToEdit = ref(null); // pass the id to this

    const editStatus = (orderId) => {
      orderIdToEdit.value = orderId;
      const orderToEdit = orders.value.find(
        (order) => order.order_detail_id === orderId
      );
      if (orderToEdit) {
        showStatusModal.value = true;
        editableOrderStatus.value = orderToEdit; // Direct assignment without spreading
        userOrderName.value = editableOrderStatus.value.username;
        selectValue.value = editableOrderStatus.value.status;
        console.log("info", editableOrderStatus.value);
      }
    };
    const closeEditStatusModal = () => {
      showStatusModal.value = false;
    };

    // request a axios to update the status for an order
    const handleEditStatusOrder = async () => {
      console.log("Status: ", selectValue.value);
      console.log("orderId:  ", orderIdToEdit.value);
      console.log("estimated date:  ", estimatedDelivery.value);
      try {
        const response = await axios.put(
          "http://localhost/Ecommerce/vue-project/src/backend/seller/sellerApi.php?action=EditStatus",
          {
            id: orderIdToEdit.value,
            status: selectValue.value,
            estimated_delivery: estimatedDelivery.value,
          }
        );
      } catch (error) {
        console.error("Error editing status:", error);
      }
      refreshPage();
    };

    // Now userLogin is directly accessible here, and it's reactive
    onMounted(() => {
      getUserFromLocalStorage();
      fetchOrders();
    });

    const fetchOrders = async () => {
      console.log("seller ", userLogin.value.store_id);
      try {
        const response = await axios.post(
          "http://localhost/Ecommerce/vue-project/src/backend/seller/sellerApi.php?action=getOrders",
          {
            store_id: userLogin.value.store_id,
          }
        );
        orders.value = response.data;
        console.log("orders: ", orders.value);
      } catch (error) {
        console.error("Error fetching orders:", error);
      }
    };

    const editData = (id) => {
      console.log(id);
    };

    return {
      orders,
      editData,
      selectValue,

      editStatus,
      showStatusModal,
      closeEditStatusModal,
      handleEditStatusOrder,
      userOrderName,
      editableOrderStatus,
      estimatedDelivery,
    };
  },
};
</script>

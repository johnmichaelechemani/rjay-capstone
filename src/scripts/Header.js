import SearchModal from "@/components/SearchModal.vue";
import LoginModal from "@/components/LoginModal.vue";
import { Icon } from "@iconify/vue";
import { onMounted, ref, computed, toRefs } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
export default {
  components: {
    Icon,
    SearchModal,
    LoginModal,
  },
  emits: ["search-completed"],

  data() {
    return {
      showSearch: false,
      showLogin: false,
      user: [],
    };
  },
  methods: {
    toggleSidebar() {
      this.isSidebarOpen = !this.isSidebarOpen;
    },
    handleSearchCompleted(data) {
      this.$emit("search-completed", data);
    },
    HandleSignIn(name) {
      this.user = name;
      this.$emit("login-completed", name);
    },
  },
  setup(props) {
    const showCart = ref(false);
    const isSidebarOpen = ref(false);
    const cartItemsValue = ref([]);
    const router = useRouter();

    const toggleSidebar = () => {
      isSidebarOpen.value = !isSidebarOpen.value;
    };

    const showCartFunction = () => {
      cartItems();
      showCart.value = !showCart.value;
      isSidebarOpen.value = false;
    };
    const closeCart = () => {
      showCart.value = false;
    };

    var userLogin = ref([]);
    // get user data from local storage
    const getUserFromLocalStorage = () => {
      try {
        const userData = localStorage.getItem("user");

        if (userData !== null) {
          userLogin.value = JSON.parse(userData);
        }

        console.log("user", userLogin.value);
        return null;
      } catch (error) {
        console.error(
          "Error while getting user data from local storage:",
          error
        );
        return error;
      }
    };

    const cartItems = async () => {
      try {
        const res = await axios.post(
          "http://localhost/Ecommerce/vue-project/src/backend/api.php?action=fetchCartItems",
          {
            cart_id: userLogin.value.user_id,
          }
        );
        cartItemsValue.value = res.data;
        // console.log(userLogin.value.user_id);
        console.log("cart value: ", cartItemsValue.value);
      } catch (error) {
        console.error("Error fetching cart items:", error);
      }
    };
    const refreshPage = () => {
      location.reload(true);
    };
    const Logout = () => {
      localStorage.removeItem("user");
      console.log("click");
      refreshPage();
      //  router.push("/admin_dashboard");
    };
    const showSettings = ref(false);
    const showCustomerSettings = () => {
      showSettings.value = !showSettings.value;
      //console.log("click");
    };
    const checkedItems = ref({});
    const isChecked = (productId) => !!checkedItems.value[productId];

    const toggleCheckbox = (productId) => {
      checkedItems.value = {
        ...checkedItems.value,
        [productId]: !isChecked(productId),
      };
      // console.log(checkedItems.value);
    };
    const checkAll = () => {
      const allProductIds = cartItemsValue.value.map((item) => item.product_id);
      const isAllChecked = allProductIds.every((productId) =>
        isChecked(productId)
      );

      const updatedCheckedItems = {};

      allProductIds.forEach((productId) => {
        updatedCheckedItems[productId] = !isAllChecked;
      });

      checkedItems.value = updatedCheckedItems;
      //console.log(checkedItems.value);
    };

    const atLeastOneItemChecked = computed(() => {
      return Object.values(checkedItems.value).some((value) => value);
    });

    // Computed property to check if all items are checked
    const allItemsChecked = computed(() => {
      const allProductIds = cartItemsValue.value.map((item) => item.product_id);
      return allProductIds.every((productId) => isChecked(productId));
    });

    // set  initial quantities from local storage or default values

    //  Update the quantity in local storage when a user changes it
    const increment = (productId) => {
      const itemIndex = cartItemsValue.value.findIndex(
        (item) => item.product_id === productId
      );
      if (itemIndex !== -1) {
        const updatedQuantity = Math.min(
          Number(cartItemsValue.value[itemIndex].quantity || 1) + 1,
          3
        );
        // Update the quantity of the specific product
        cartItemsValue.value[itemIndex].quantity = updatedQuantity;
      }
    };

    //  update the quantity in local storage
    const decrement = (productId) => {
      const itemIndex = cartItemsValue.value.findIndex(
        (item) => item.product_id === productId
      );
      if (itemIndex !== -1) {
        const updatedQuantity = Math.min(
          Number(cartItemsValue.value[itemIndex].quantity || 1) - 1,
          3
        );
        // Update the quantity of the specific product
        cartItemsValue.value[itemIndex].quantity = updatedQuantity;
      }
    };
    const deleteCartItems = async (cart_id) => {
      let id = cart_id;
      // console.log(id);
      try {
        const res = await axios.delete(
          `http://localhost/Ecommerce/vue-project/src/backend/api.php?action=deleteCartItem`,
          {
            data: { id },
            headers: {
              "Content-Type": "application/json",
            },
          }
        );
        if (res.data.success) {
          cartItems();
        }
        //  console.log(res);
      } catch (error) {
        console.error(error);
      }
    };

    // select payment method
    const showPayment = ref(false);

    const checkoutItems = computed(() => {
      const checkedItemIds = Object.keys(checkedItems.value).filter(
        (productId) => checkedItems.value[productId]
      );

      return cartItemsValue.value.filter((item) =>
        checkedItemIds.includes(item.product_id.toString())
      );
    });

    const itemsToCheckout = ref({});
    let priceTotalAll = ref(0);
    let priceTotalPerItem = ref(0);
    // shipping fee must come from db
    const selectedPayment = ref("");

    const checkout = () => {
      showPayment.value = true;
      console.log("checked out items value", checkoutItems.value);

      // Reset the total price before calculation
      priceTotalPerItem.value = 0;

      // Collect all the checked items and calculate the total price
      itemsToCheckout.value = checkoutItems.value.map((item) => {
        // Calculate price per item including shipping fee
        const pricePerItem =
          item.quantity * parseFloat(item.price) +
          parseFloat(item.shipping_fee);

        // Add price per item to the total
        priceTotalPerItem.value += pricePerItem;

        // Return item with pricePerItem if you need it, or just return the item
        return { item }; // Spread operator to include original item properties
      });

      // Assuming priceTotalPerItem is already the total price for all items
      // No need to add pricePerItem again
      priceTotalAll.value = priceTotalPerItem.value.toFixed(2);
    };

    const onDelivery = () => {
      selectedPayment.value = "cash on delivery";
    };
    const onPyment = () => {
      selectedPayment.value = "payment";
    };
    const onCredit = () => {
      selectedPayment.value = "credit";
    };

    const closePayment = () => {
      showPayment.value = false;

      priceTotalAll.value = 0;
      priceTotalPerItem.value = 0;
      console.log("click");
    };

    const submitOrder = async () => {
      console.log(priceTotalAll.value); // Assuming priceTotalAll is a reactive reference
      console.log(selectedPayment.value); // Assuming selectedPayment is a reactive reference
      console.log(userLogin.value.user_id); // Assuming userLogin is a reactive reference
      console.log("checkoutItems length", checkoutItems.value.length);

      // Directly use the value for the API call
      let userID = userLogin.value.user_id;
      let totalPrice = priceTotalAll.value;
      let orderStatus = "pending";
      let numofItems = checkoutItems.value.length;
      let payment = selectedPayment.value;

      // This is for Order details
      let ids = checkoutItems.value.map((item) => item.product_id);
      console.log("product IDs", ids);
      let quantities = checkoutItems.value.map((item) => item.quantity);
      console.log("Quantities", quantities);
      let productsTotalIncludingShipping = checkoutItems.value.map((item) =>
        parseFloat(
          (
            parseFloat(item.price) * item.quantity +
            parseFloat(item.shipping_fee)
          ).toFixed(2)
        )
      );
      console.log(
        "Total Prices including shipping",
        productsTotalIncludingShipping
      );

      // API call
      try {
        const res = await axios.post(
          `http://localhost/Ecommerce/vue-project/src/backend/api.php?action=CheckoutOrder`,
          {
            user_id: userID,
            total_price: totalPrice,
            status: orderStatus,
            item: numofItems,
            product_id: ids,
            quantity: quantities,
            price: productsTotalIncludingShipping,
            payment_method: payment,
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        );
        console.log("Response from server", res.data);
      } catch (error) {
        console.error(error);
      }
      refreshPage();
    };

    getUserFromLocalStorage();
    cartItems();

    //order tracking
    // const orderData = [
    //   {
    //     id: 1,
    //     product_id: 1,
    //     order_id: 3456789,
    //     total_price: 130,
    //     product_name: "Monitor 16in 144hz",
    //     status: "processing",
    //     date_purchased: "Fri, Dec 1, 2024",
    //     date_delivery: "Fri, Dec 25, 2024",
    //   },
    //   {
    //     id: 2,
    //     product_id: 2,
    //     order_id: 123455,
    //     total_price: 300,
    //     product_name: "Intel core I9 13gen",
    //     status: "out_for_delivery",
    //     date_purchased: "Fri, Dec 1, 2024",
    //     date_delivery: "Fri, Dec 25, 2024",
    //   },
    //   {
    //     id: 3,
    //     product_id: 3,
    //     order_id: 123455,
    //     total_price: 400,
    //     product_name: "Intel core I9 13gen",
    //     status: "delivered",
    //     date_purchased: "Fri, Dec 1, 2024",
    //     date_delivery: "Fri, Dec 25, 2024",
    //   },
    // ];
    const orderData = ref([]);
    const getTrackingOrder = async () => {
      try {
        const res = await axios.post(
          "http://localhost/Ecommerce/vue-project/src/backend/api.php?action=getTrackOrder",
          {
            id: userLogin.value.user_id, // get the user id
          }
        );

        // Assuming res.data.order_records is an array
        const transformedData = res.data.order_records.map((item) => ({
          ...item,
          status: statusMapping[item.status.toLowerCase().replace(/\s+/g, "_")], // transform the status
        }));

        orderData.value = transformedData; // Update the reactive variable with the transformed data
        console.log("order value: ", orderData.value);
      } catch (error) {
        console.error("Error fetching orders :", error); // return an error
      }
    };

    getTrackingOrder();

    const statusMapping = {
      pending: 1,
      processing: 2,
      out_for_delivery: 3,
      delivered: 4,
    };

    const showOrderTracking = ref(false);
    const orderTracking = (e) => {
      showOrderTracking.value = !showOrderTracking.value;
    };
    const closeOrderTracking = (e) => {
      showOrderTracking.value = false;
    };

    return {
      //tracking
      showOrderTracking,
      orderTracking,
      closeOrderTracking,
      orderData,
      //----------
      showCartFunction,
      showCart,
      closeCart,

      toggleSidebar,

      isSidebarOpen,
      cartItemsValue,

      userLogin,
      Logout,

      showCustomerSettings,
      showSettings,

      isChecked,
      toggleCheckbox,

      checkAll,
      atLeastOneItemChecked,
      allItemsChecked,
      checkout,

      // edit quantity
      increment,
      decrement,

      //
      deleteCartItems,
      //
      showPayment,
      closePayment,

      itemsToCheckout,
      priceTotalAll,

      onDelivery,
      onCredit,
      onPyment,
      submitOrder,
      selectedPayment,
    };
  },
};

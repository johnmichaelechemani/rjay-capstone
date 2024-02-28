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
    // ... other methods ...
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
    let shippingFee = ref(10);
    const selectedPayment = ref("");

    const checkout = () => {
      showPayment.value = true;
      // Collect all the checked items
      itemsToCheckout.value = checkoutItems.value.map((item) => {
        // Calculate price per item
        const pricePerItem = item.quantity * item.price;
        // Add price per item to the total per item
        priceTotalPerItem.value += pricePerItem;
        // Return item with additional calculated values
        return { item };
      });
      // Calculate the total price for all items
      priceTotalAll.value = (
        priceTotalPerItem.value + shippingFee.value
      ).toFixed(2);
    };
    const onDelivery = () => {
      selectedPayment.value = "delivery";
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
      // shipping fee must come from db
      shippingFee.value = 10;
      console.log("click");
    };
    const submitOrder = () => {
      console.log(priceTotalAll.value);
      console.log(selectedPayment.value);
      console.log(userLogin.value.user_id);
    };

    getUserFromLocalStorage();
    cartItems();

    //order tracking
    const orderData = [
      {
        id: 1,
        product_id: 1,
        order_id: 3456789,
        total_price: 130,
        product_name: "Monitor 16in 144hz",
        status: "Pending",
        date_purchased: "Fri, Dec 1, 2024",
        date_delivery: "Fri, Dec 25, 2024",
      },
      {
        id: 2,
        product_id: 2,
        order_id: 123455,
        total_price: 300,
        product_name: "Intel core I9 13gen",
        status: "Processing",
        date_purchased: "Fri, Dec 1, 2024",
        date_delivery: "Fri, Dec 25, 2024",
      },
      {
        id: 3,
        product_id: 3,
        order_id: 123455,
        total_price: 400,
        product_name: "Intel core I9 13gen",
        status: "Shipping",
        date_purchased: "Fri, Dec 1, 2024",
        date_delivery: "Fri, Dec 25, 2024",
      },
    ];
    const statusMapping = {
      Pending: 1,
      Processing: 2,
      Shipping: 3,
    };
    orderData.forEach((item) => {
      item.status = statusMapping[item.status];
    });
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

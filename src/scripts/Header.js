import SearchModal from "@/components/SearchModal.vue";
import LoginModal from "@/components/LoginModal.vue";
import { Icon } from "@iconify/vue";
import { onMounted, ref, computed } from "vue";
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
    const showWishList = ref(false);
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
      showWishList.value = false;
    };
    const showWishListFunction = () => {
      showWishList.value = !showWishList.value;
      isSidebarOpen.value = false;
      showCart.value = false;
    };
    const closeCart = () => {
      showCart.value = false;
    };
    const closeWishList = () => {
      showWishList.value = false;
    };

    var userLogin = ref([]);
    const getUserFromLocalStorage = () => {
      const userData = localStorage.getItem("user");
      if (userData) {
        userLogin.value = JSON.parse(userData);
      }
      return null;
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
        // console.log("cart value: ", cartItemsValue.value);
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

    const checkout = () => {
      // Collect all the checked item IDs
      const checkedItemIds = Object.keys(checkedItems.value).filter(
        (productId) => checkedItems.value[productId]
      );
      console.log("Checked Item IDs:", checkedItemIds);
    };

    getUserFromLocalStorage();
    cartItems();

    return {
      showCartFunction,
      showCart,
      closeCart,
      showWishList,
      showWishListFunction,
      closeWishList,
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
    };
  },
};

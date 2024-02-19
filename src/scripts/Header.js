import SearchModal from "@/components/SearchModal.vue";
import LoginModal from "@/components/LoginModal.vue";
import { Icon } from "@iconify/vue";
import { onMounted, ref } from "vue";
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

    const userLogin = ref([]);

    const getUserFromLocalStorage = () => {
      const userData = localStorage.getItem("user");
      if (userData) {
        userLogin.value = JSON.parse(userData);
      }
      return null;
    };

    const cart_id = userLogin.value.user_id;
    console.log("id", cart_id);

    const cartItems = async () => {
      try {
        const res = await axios.post(
          "http://localhost/Ecommerce/vue-project/src/backend/api.php?action=fetchCartItems",
          {
            cart_id: cart_id,
          }
        );
        cartItemsValue.value = res.data;
        console.log(cartItemsValue);
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

    onMounted(() => {
      getUserFromLocalStorage();
      cartItems();
    });
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
    };
  },
};

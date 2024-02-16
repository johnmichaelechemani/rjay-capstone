import SearchModal from "@/components/SearchModal.vue";
import LoginModal from "@/components/LoginModal.vue";
import { Icon } from "@iconify/vue";
import { onMounted, ref } from "vue";
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

    const cartItems = async () => {
      try {
        const res = await axios.get(
          "http://localhost/Ecommerce/vue-project/src/backend/api.php?action=fetchCartItems"
        );
        cartItemsValue.value = res.data;
      } catch (error) {
        console.error("Error fetching cart items:", error);
      }
    };

    onMounted(() => {
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
    };
  },
};

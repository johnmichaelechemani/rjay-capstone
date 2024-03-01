import { createRouter, createWebHistory } from "vue-router";
import Customer from "../views/Customer.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      redirect: "/home",
    },
    {
      path: "/home",
      name: "home",
      component: Customer,
    },
    {
      path: "/products",
      name: "products",
      component: () => import("../components/Products.vue"),
    },
    {
      path: "/search_products",
      name: "search_products",
      component: () => import("../views/SearchProducts.vue"),
    },
    {
      path: "/admin_dashboard",
      name: "admin_dashboard",
      component: () => import("../Admin/adminViews/adminDashboard.vue"),
      children: [
        {
          path: "",
          redirect: { name: "admin_dashboard_customers" },
        },
        {
          path: "",
          name: "users",
          component: () =>
            import("../Admin/adminViews/adminManageCustomers.vue"),
        },
        {
          path: "/users", // Example nested route
          name: "users",
          component: () => import("../Admin/adminViews/AdminUsers.vue"),
          children: [
            {
              path: "",
              name: "admin_dashboard_customers",
              component: () =>
                import("../Admin/adminViews/adminManageCustomers.vue"),
            },
            {
              path: "/admin_dashboard_customers",
              name: "admin_dashboard_customers",
              component: () =>
                import("../Admin/adminViews/adminManageCustomers.vue"),
            },
            {
              path: "/admin_dashboard_sellers",
              name: "dashboard_sellers",
              component: () =>
                import("../Admin/adminViews/adminManageSellers.vue"),
            },
          ],
        },

        {
          path: "/admin_stores",
          name: "admin_stores",
          component: () => import("../Admin/adminViews/adminStoreRequest.vue"),
        },
      ],
    },
    {
      path: "/seller_dashboard",
      name: "seller_dashboard",
      component: () => import("../Sellers/sellersViews/sellerDashboard.vue"),
      children: [
        {
          path: "",
          redirect: { name: "seller_products" },
        },
        {
          path: "/seller_products",
          name: "seller_products",
          component: () => import("../Sellers/sellersViews/sellerProducts.vue"),
        },
        {
          path: "/seller_Add_products",
          name: "seller_Add_products",
          component: () =>
            import("../Sellers/sellersViews/sellerAddProducts.vue"),
        },

        {
          path: "/seller_order",
          name: "seller_order",
          component: () =>
            import("../Sellers/sellersViews/sellerOrderManagement.vue"),
        },

        {
          path: "/seller_customers",
          name: "seller_customers",
          component: () =>
            import("../Sellers/sellersViews/sellerCustomers.vue"),
        },
        {
          path: "/seller_transations",
          name: "seller_transations",
          component: () =>
            import("../Sellers/sellersViews/sellerTransactions.vue"),
        },
        {
          path: "/seller_product_list",
          name: "seller_product_list",
          component: () =>
            import("../Sellers/sellersViews/sellerProductList.vue"),
        },
      ],
    },
    {
      path: "/seller_index",
      name: "seller_index",
      component: () => import("../Sellers/sellerIndex.vue"),
      children: [
        {
          path: "",
          redirect: { name: "seller_login" },
        },
        {
          path: "/seller_login",
          name: "seller_login",
          component: () =>
            import("../Sellers/sellersComponents/sellerLogin.vue"),
        },
        {
          path: "/seller_register",
          name: "seller_register",
          component: () =>
            import("../Sellers/sellersComponents/sellerregister.vue"),
        },
      ],
    },
    {
      path: "/:catchAll(.*)",
      name: "notFound",
      component: () => import("../views/NotFound.vue"),
    },
  ],
});

export default router;
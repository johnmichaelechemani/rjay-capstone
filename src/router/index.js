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
              name: "dashboard_customers",
              component: () =>
                import("../Admin/adminViews/adminManageCustomers.vue"),
            },
            {
              path: "/admin_dashboard_customers",
              name: "dashboard_customers",
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
          path: "/seller_order_management",
          name: "seller_order_management",
          component: () =>
            import(
              "../Sellers/sellersViews/order-management/sellerOrderManagement.vue"
            ),
          children: [
            {
              path: "",
              redirect: { name: "seller_order_management_pending" },
            },
            {
              path: "/seller_order_management_pending",
              name: "seller_order_management_pending",
              component: () =>
                import(
                  "../Sellers/sellersViews/order-management/sellerOrderManagementPending.vue"
                ),
            },
            {
              path: "/seller_order_management_confirmed",
              name: "seller_order_management_confirmed",
              component: () =>
                import(
                  "../Sellers/sellersViews/order-management/sellerOrderManagementConfirmed.vue"
                ),
            },
          ],
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
      path: "/:catchAll(.*)",
      name: "notFound",
      component: () => import("../views/NotFound.vue"),
    },
  ],
});

export default router;

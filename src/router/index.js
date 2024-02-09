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
        // Add more nested routes as needed
      ],
    },
    {
      path: "/seller_dashboard",
      name: "seller_dashboard",
      component: () => import("../Sellers/sellersViews/sellerDashboard.vue"),
      children: [
        {
          path: "",
          name: "seller_products",
          component: () => import("../Sellers/sellersViews/sellerProducts.vue"),
        },
        {
          path: "/seller_products",
          name: "seller_products",
          component: () => import("../Sellers/sellersViews/sellerProducts.vue"),
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

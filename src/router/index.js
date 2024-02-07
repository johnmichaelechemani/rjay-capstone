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
          path: "", // Empty path for the default view (dashboard)
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
              path: "", // Empty path for the default view (dashboard)
              name: "dashboard_customers",
              component: () =>
                import("../Admin/adminViews/adminManageCustomers.vue"),
            },
            {
              path: "/admin_dashboard_customers", // Empty path for the default view (dashboard)
              name: "dashboard_customers",
              component: () =>
                import("../Admin/adminViews/adminManageCustomers.vue"),
            },
            {
              path: "/admin_dashboard_sellers", // Empty path for the default view (dashboard)
              name: "dashboard_sellers",
              component: () =>
                import("../Admin/adminViews/adminManageSellers.vue"),
            },
          ],
        },
        // Add more nested routes as needed
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

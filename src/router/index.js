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
    },
    {
      path: "/:catchAll(.*)",
      name: "notFound",
      component: () => import("../views/NotFound.vue"),
    },
  ],
});

export default router;

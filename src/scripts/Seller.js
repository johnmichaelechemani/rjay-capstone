// Seller.js
import { ref } from "vue";

export const userLogin = ref({});

// Function to update userLogin from localStorage, if needed
export function getUserFromLocalStorage() {
  const userData = localStorage.getItem("seller");
  if (userData) {
    userLogin.value = JSON.parse(userData);
  }
}
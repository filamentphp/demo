import { defineStore } from 'pinia';
const CART_STORAGE_KEY = 'cart_items';


export const useCartStore = defineStore('cart', {
    state: () => ({
        items: JSON.parse(localStorage.getItem(CART_STORAGE_KEY)) || [], // Load items from localStorage on store initialization
    }),
  getters: {
    totalItems() {
      return this.items.length;
    },
    isItemInCart(state) {
        return (item) => {
          return state.items.some((cartItem) => cartItem.id === item.id);
        };
    },
  },
  actions: {
    addToCart(item) {
        if (!this.isItemInCart(item)) {
            this.items.push(item);
            this.saveToLocalStorage();
        }
    },
    removeFromCart(index) {
        this.items.splice(index, 1);
        this.saveToLocalStorage();
    },
    saveToLocalStorage() {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(this.items));
    },
    loadFromLocalStorage() {
        const savedItems = JSON.parse(localStorage.getItem(CART_STORAGE_KEY));
        if (savedItems) {
          this.items = savedItems;
        }
    },
  },
});

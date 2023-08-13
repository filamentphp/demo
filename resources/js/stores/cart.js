import { defineStore } from 'pinia';
const CART_STORAGE_KEY = 'cart_items';


export const useCartStore = defineStore('cart', {
    state: () => ({
        items: JSON.parse(localStorage.getItem(CART_STORAGE_KEY)) || [],
        customAddons: [], // Load items from localStorage on store initialization
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
    totalAmount(state) {
        return state.items.reduce((total, item) => total + Number(item.price) * item.quantity, 0);
    },
  },
  actions: {
    addToCart(item, includedPatch, CustNames) {
        const existingItem = this.items.find((i) => i.id === item.id);

        if (existingItem) {
            existingItem.quantity++;
            this.addCustomAddon({
                itemIndex: this.items.indexOf(existingItem),
                includedPatch,
                CustNames,
            });
        } else {
            this.items.push({ ...item, quantity: 1, customAddons: [] }); // Initialize customAddons array
            this.addCustomAddon({
                itemIndex: this.items.length - 1,
                includedPatch,
                CustNames,
            });
        }
    },
    addCustomAddon({ itemIndex, includedPatch, CustNames }) {
        this.items[itemIndex].customAddons.push({
            patchChosen: includedPatch,
            CustomNameAdded: CustNames,
        });

        this.saveToLocalStorage(); // Update localStorage after adding custom addons
    },
    removeFromCart(index) {
        const itemIndex = this.items.findIndex((i) => i.id === this.items[index].id);

        if (itemIndex !== -1) {
            const cartItem = this.items[itemIndex];
            if (cartItem.quantity > 1) {
                cartItem.quantity--;
            } else {
                this.items.splice(itemIndex, 1);
            }

            // Remove custom addons along with the item
            cartItem.customAddons = [];

            this.saveToLocalStorage(); // Update localStorage after item removal
        }
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

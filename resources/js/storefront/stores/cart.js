import { defineStore } from 'pinia';

const STORAGE_KEY = 'auto_parts_shop_cart';

const parseNumber = (value, fallback = 0) => {
  const n = Number(value);
  return Number.isFinite(n) ? n : fallback;
};

const readStorage = () => {
  if (typeof window === 'undefined') return [];

  try {
    const raw = window.localStorage.getItem(STORAGE_KEY);
    if (!raw) return [];

    const parsed = JSON.parse(raw);
    if (!Array.isArray(parsed)) return [];

    return parsed
      .map((item) => ({
        id: parseNumber(item.id),
        sku: String(item.sku || ''),
        name: String(item.name || ''),
        price: parseNumber(item.price),
        quantity: Math.max(1, parseNumber(item.quantity, 1)),
        image: String(item.image || ''),
      }))
      .filter((item) => item.id > 0 && item.name !== '');
  } catch {
    return [];
  }
};

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: readStorage(),
  }),

  getters: {
    totalItems: (state) => state.items.reduce((sum, item) => sum + item.quantity, 0),
    totalAmount: (state) => state.items.reduce((sum, item) => sum + item.price * item.quantity, 0),
  },

  actions: {
    persist() {
      if (typeof window === 'undefined') return;
      window.localStorage.setItem(STORAGE_KEY, JSON.stringify(this.items));
    },

    addItem(product, qty = 1) {
      const quantity = Math.max(1, parseNumber(qty, 1));
      const productId = parseNumber(product.id);
      if (productId <= 0) return;

      const existing = this.items.find((item) => item.id === productId);
      if (existing) {
        existing.quantity += quantity;
      } else {
        this.items.push({
          id: productId,
          sku: product.sku || '',
          name: product.name || 'Товар',
          price: parseNumber(product.price),
          quantity,
          image: product.image || '',
        });
      }

      this.persist();
    },

    setQuantity(id, value) {
      const item = this.items.find((row) => row.id === parseNumber(id));
      if (!item) return;

      item.quantity = Math.max(1, parseNumber(value, 1));
      this.persist();
    },

    increment(id) {
      const item = this.items.find((row) => row.id === parseNumber(id));
      if (!item) return;
      item.quantity += 1;
      this.persist();
    },

    decrement(id) {
      const item = this.items.find((row) => row.id === parseNumber(id));
      if (!item) return;

      if (item.quantity <= 1) {
        this.removeItem(id);
        return;
      }

      item.quantity -= 1;
      this.persist();
    },

    removeItem(id) {
      this.items = this.items.filter((row) => row.id !== parseNumber(id));
      this.persist();
    },

    clear() {
      this.items = [];
      this.persist();
    },
  },
});

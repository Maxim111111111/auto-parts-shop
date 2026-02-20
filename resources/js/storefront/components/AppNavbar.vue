<script setup>
import { computed, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useCartStore } from '../stores/cart';

const route = useRoute();
const cart = useCartStore();

const menuOpen = ref(false);
const cartCount = computed(() => cart.totalItems);
const bump = ref(false);

watch(
  () => route.fullPath,
  () => {
    menuOpen.value = false;
  },
);

watch(cartCount, (next, prev) => {
  if (next === prev) return;
  bump.value = true;
  window.setTimeout(() => {
    bump.value = false;
  }, 260);
});
</script>

<template>
  <header class="topbar">
    <div class="topbar-inner">
      <RouterLink to="/" class="logo">Auto Parts Shop</RouterLink>

      <button class="menu-btn" type="button" @click="menuOpen = !menuOpen" :aria-expanded="menuOpen ? 'true' : 'false'">
        <span>{{ menuOpen ? 'Закрыть' : 'Меню' }}</span>
      </button>

      <nav :class="['menu', { open: menuOpen }]">
        <RouterLink to="/" class="menu-link" active-class="active">Главная</RouterLink>
        <RouterLink to="/catalog" class="menu-link" active-class="active">Каталог</RouterLink>
        <RouterLink to="/cart" class="menu-link cart-link" active-class="active">
          <span>Корзина</span>
          <span :class="['cart-pill', { 'is-bump': bump }]">{{ cartCount }}</span>
        </RouterLink>
        <a class="menu-link" href="/admin">Админка</a>
      </nav>
    </div>
  </header>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useCartStore } from '../stores/cart';
import { useAuth } from '../composables/useAuth';

const route = useRoute();
const cart = useCartStore();
const { user, isAuthenticated, isAdmin, ensureLoaded, logout } = useAuth();

const menuOpen = ref(false);
const cartCount = computed(() => cart.totalItems);
const bump = ref(false);
const authLabel = computed(() => (user.value?.name ? `Профиль (${user.value.name})` : 'Профиль'));

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

onMounted(() => {
  ensureLoaded();
});

const handleLogout = async () => {
  await logout();
  window.location.assign('/');
};
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

        <template v-if="isAuthenticated">
          <a class="menu-link" href="/profile">{{ authLabel }}</a>
          <button type="button" class="menu-link menu-link-button" @click="handleLogout">Выйти</button>
        </template>
        <template v-else>
          <a class="menu-link" href="/login">Войти</a>
        </template>

        <a v-if="isAdmin" class="menu-link" href="/admin">Админка</a>
      </nav>
    </div>
  </header>
</template>

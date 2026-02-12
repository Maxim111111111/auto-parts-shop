<script setup>
import AppNavbar from './components/AppNavbar.vue';
import AppFooter from './components/AppFooter.vue';
</script>

<template>
  <div class="shop-app">
    <AppNavbar />
    <main class="page-wrap">
      <router-view />
    </main>
    <AppFooter />
  </div>
</template>

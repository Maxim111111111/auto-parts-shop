<script setup>
defineProps({
  category: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <RouterLink :to="{ name: 'category', params: { id: category.id } }" class="category-card reveal-up">
    <img :src="category.image" :alt="category.title" loading="lazy" />

    <div class="category-overlay" />

    <div class="category-body">
      <h3>{{ category.title }}</h3>
      <p>{{ category.count }} товаров</p>
    </div>
  </RouterLink>
</template>

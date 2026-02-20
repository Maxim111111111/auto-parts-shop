<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import ProductCard from '../components/ProductCard.vue';
import { useCatalog } from '../composables/useCatalog';

const route = useRoute();
const { loading, error, products, findCategory, ensureLoaded } = useCatalog();

const brand = ref('all');
const minPrice = ref('');
const maxPrice = ref('');

const categoryId = computed(() => Number(route.params.id || 0));
const category = computed(() => findCategory(categoryId.value));

const sourceProducts = computed(() =>
  products.value.filter((item) => item.categoryId === categoryId.value),
);

const brands = computed(() => {
  const unique = [...new Set(sourceProducts.value.map((item) => item.brand).filter(Boolean))];
  return unique.sort((a, b) => a.localeCompare(b, 'ru'));
});

const filteredProducts = computed(() => {
  const min = Number(minPrice.value || 0);
  const max = Number(maxPrice.value || 0);

  let list = [...sourceProducts.value];

  if (brand.value !== 'all') {
    list = list.filter((item) => item.brand === brand.value);
  }

  if (minPrice.value !== '') {
    list = list.filter((item) => item.price >= min);
  }

  if (maxPrice.value !== '') {
    list = list.filter((item) => item.price <= max);
  }

  return list;
});

onMounted(ensureLoaded);
</script>

<template>
  <section class="container">
    <div class="section-head">
      <h1>{{ category?.title || 'Категория' }}</h1>
      <RouterLink to="/catalog" class="section-link">Назад к каталогу</RouterLink>
    </div>

    <p v-if="error" class="inline-error">{{ error }}</p>
    <p v-else-if="loading" class="inline-info">Загрузка категории...</p>
    <p v-else-if="!category" class="inline-error">Категория не найдена.</p>

    <template v-else>
      <div class="filter-grid compact">
        <label>
          <span>Бренд</span>
          <select v-model="brand">
            <option value="all">Все бренды</option>
            <option v-for="item in brands" :key="item" :value="item">{{ item }}</option>
          </select>
        </label>

        <label>
          <span>Цена от</span>
          <input v-model="minPrice" type="number" min="0" placeholder="0" />
        </label>

        <label>
          <span>Цена до</span>
          <input v-model="maxPrice" type="number" min="0" placeholder="50000" />
        </label>
      </div>

      <p class="result-count">Товаров в категории: {{ filteredProducts.length }}</p>

      <div class="product-grid">
        <ProductCard v-for="product in filteredProducts" :key="product.id" :product="product" />
      </div>
    </template>
  </section>
</template>

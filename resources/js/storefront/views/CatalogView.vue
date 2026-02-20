<script setup>
import { computed, onMounted, ref } from 'vue';
import ProductCard from '../components/ProductCard.vue';
import { useCatalog } from '../composables/useCatalog';

const { loading, error, products, ensureLoaded } = useCatalog();

const search = ref('');
const brand = ref('all');
const minPrice = ref('');
const maxPrice = ref('');
const sort = ref('default');

const brands = computed(() => {
  const unique = [...new Set(products.value.map((item) => item.brand).filter(Boolean))];
  return unique.sort((a, b) => a.localeCompare(b, 'ru'));
});

const filteredProducts = computed(() => {
  const q = search.value.trim().toLowerCase();
  const min = Number(minPrice.value || 0);
  const max = Number(maxPrice.value || 0);

  let list = [...products.value];

  if (q) {
    list = list.filter((item) =>
      [item.name, item.brand, item.sku, item.category]
        .join(' ')
        .toLowerCase()
        .includes(q),
    );
  }

  if (brand.value !== 'all') {
    list = list.filter((item) => item.brand === brand.value);
  }

  if (minPrice.value !== '') {
    list = list.filter((item) => item.price >= min);
  }

  if (maxPrice.value !== '') {
    list = list.filter((item) => item.price <= max);
  }

  if (sort.value === 'price_asc') list.sort((a, b) => a.price - b.price);
  if (sort.value === 'price_desc') list.sort((a, b) => b.price - a.price);
  if (sort.value === 'stock_desc') list.sort((a, b) => b.stock - a.stock);

  return list;
});

onMounted(ensureLoaded);
</script>

<template>
  <section class="container">
    <div class="section-head">
      <h1>Каталог</h1>
      <RouterLink to="/" class="section-link">На главную</RouterLink>
    </div>

    <div class="filter-grid">
      <label>
        <span>Поиск</span>
        <input v-model="search" type="text" placeholder="Название, бренд, SKU" />
      </label>

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

      <label>
        <span>Сортировка</span>
        <select v-model="sort">
          <option value="default">Сначала новые</option>
          <option value="price_asc">Сначала дешевле</option>
          <option value="price_desc">Сначала дороже</option>
          <option value="stock_desc">Больше остаток</option>
        </select>
      </label>
    </div>

    <p v-if="error" class="inline-error">{{ error }}</p>
    <p v-else-if="loading" class="inline-info">Загрузка каталога...</p>

    <div v-else>
      <p class="result-count">Найдено: {{ filteredProducts.length }}</p>
      <div class="product-grid">
        <ProductCard v-for="product in filteredProducts" :key="product.id" :product="product" :show-category="true" />
      </div>
    </div>
  </section>
</template>

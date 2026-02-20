<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import BaseButton from '../components/BaseButton.vue';
import QuantitySelector from '../components/QuantitySelector.vue';
import { useCartStore } from '../stores/cart';
import { useCatalog } from '../composables/useCatalog';

const route = useRoute();
const cart = useCartStore();
const { loading, error, products, findProduct, ensureLoaded } = useCatalog();

const quantity = ref(1);
const activeImage = ref(0);
const activeTab = ref('description');

const currency = new Intl.NumberFormat('ru-RU', {
  style: 'currency',
  currency: 'RUB',
  maximumFractionDigits: 0,
});

const productId = computed(() => Number(route.params.id || 0));
const product = computed(() => findProduct(productId.value));

const relatedProducts = computed(() => {
  if (!product.value) return [];

  return products.value
    .filter((item) => item.id !== product.value.id && item.categoryId === product.value.categoryId)
    .slice(0, 4);
});

const backRoute = computed(() => {
  if (product.value?.categoryId) {
    return { name: 'category', params: { id: product.value.categoryId } };
  }

  return { name: 'catalog' };
});

const specItems = computed(() => {
  if (!product.value) return [];

  return [
    { label: 'SKU', value: product.value.sku || '—' },
    { label: 'Бренд', value: product.value.brand || 'Без бренда' },
    { label: 'Категория', value: product.value.category || 'Без категории' },
    { label: 'Остаток', value: `${product.value.stock} шт` },
  ];
});

const price = (value) => currency.format(Number(value || 0));

const addToCart = () => {
  if (!product.value || product.value.stock <= 0) return;
  cart.addItem(product.value, quantity.value);
};

watch(productId, () => {
  quantity.value = 1;
  activeImage.value = 0;
  activeTab.value = 'description';
});

onMounted(ensureLoaded);
</script>

<template>
  <section class="container product-page">
    <p v-if="error" class="inline-error">{{ error }}</p>
    <p v-else-if="loading" class="inline-info">Загрузка товара...</p>
    <p v-else-if="!product" class="inline-error">Товар не найден.</p>

    <template v-else>
      <div class="section-head">
        <h1>{{ product.name }}</h1>
        <RouterLink :to="backRoute" class="section-link">Назад</RouterLink>
      </div>

      <div class="product-detail reveal-up">
        <div class="gallery-card">
          <img class="main-photo" :src="product.images[activeImage]" :alt="product.name" />

          <div class="thumb-grid">
            <button
              v-for="(image, index) in product.images"
              :key="image"
              type="button"
              :class="['thumb-btn', { active: index === activeImage }]"
              @click="activeImage = index"
            >
              <img :src="image" :alt="`${product.name} ${index + 1}`" />
            </button>
          </div>
        </div>

        <div class="detail-card">
          <p class="sku">SKU: {{ product.sku }}</p>
          <p class="product-meta">Категория: {{ product.category }}</p>
          <p class="product-meta">Бренд: {{ product.brand || 'Без бренда' }}</p>

          <p class="detail-price">{{ price(product.price) }}</p>
          <p class="stock-pill" :class="{ warning: product.stock > 0 && product.stock <= 5, danger: product.stock <= 0 }">
            В наличии: {{ product.stock }} шт
          </p>

          <div class="subtle-divider" />

          <div class="quantity-row">
            <label for="qty">Количество</label>
            <QuantitySelector id="qty" v-model="quantity" :max="Math.max(1, product.stock)" :disabled="product.stock <= 0" />
          </div>

          <BaseButton variant="primary" size="lg" :disabled="product.stock <= 0" @click="addToCart">Добавить в корзину</BaseButton>
        </div>
      </div>

      <section class="detail-tabs reveal-up">
        <div class="tab-list" role="tablist" aria-label="Информация о товаре">
          <button type="button" :class="['tab-btn', { active: activeTab === 'description' }]" @click="activeTab = 'description'">
            Описание
          </button>
          <button type="button" :class="['tab-btn', { active: activeTab === 'specs' }]" @click="activeTab = 'specs'">
            Характеристики
          </button>
        </div>

        <div class="tab-panel" v-if="activeTab === 'description'">
          <p>{{ product.description }}</p>
        </div>

        <div class="tab-panel" v-else>
          <ul class="spec-list">
            <li v-for="item in specItems" :key="item.label">
              <span>{{ item.label }}</span>
              <strong>{{ item.value }}</strong>
            </li>
          </ul>
        </div>
      </section>

      <section v-if="relatedProducts.length" class="related-block reveal-up">
        <div class="section-head">
          <h2>Похожие товары</h2>
          <RouterLink to="/catalog" class="section-link">В каталог</RouterLink>
        </div>

        <div class="mini-grid">
          <RouterLink
            v-for="item in relatedProducts"
            :key="item.id"
            :to="{ name: 'product', params: { id: item.id } }"
            class="mini-card"
          >
            <img :src="item.image" :alt="item.name" loading="lazy" />
            <p>{{ item.name }}</p>
            <strong>{{ price(item.price) }}</strong>
          </RouterLink>
        </div>
      </section>
    </template>
  </section>
</template>

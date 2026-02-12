<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useCartStore } from '../stores/cart';
import { useCatalog } from '../composables/useCatalog';

const route = useRoute();
const cart = useCartStore();
const { loading, error, products, findProduct, ensureLoaded } = useCatalog();

const quantity = ref(1);
const activeImage = ref(0);

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

const price = (value) => currency.format(Number(value || 0));

const addToCart = () => {
  if (!product.value) return;
  cart.addItem(product.value, quantity.value);
};

watch(productId, () => {
  quantity.value = 1;
  activeImage.value = 0;
});

onMounted(ensureLoaded);
</script>

<template>
  <section class="container">
    <p v-if="error" class="inline-error">{{ error }}</p>
    <p v-else-if="loading" class="inline-info">Загрузка товара...</p>
    <p v-else-if="!product" class="inline-error">Товар не найден.</p>

    <template v-else>
      <div class="section-head">
        <h1>{{ product.name }}</h1>
        <RouterLink :to="backRoute">Назад к категории</RouterLink>
      </div>

      <div class="product-detail">
        <div>
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

        <div class="detail-info">
          <p class="sku">SKU: {{ product.sku }}</p>
          <p class="product-meta">Категория: {{ product.category }}</p>
          <p class="product-meta">Бренд: {{ product.brand || 'Без бренда' }}</p>

          <p class="detail-price">{{ price(product.price) }}</p>
          <p class="product-meta">В наличии: {{ product.stock }} шт</p>

          <p class="detail-description">{{ product.description }}</p>

          <div class="quantity-row">
            <label for="qty">Количество</label>
            <input id="qty" v-model.number="quantity" type="number" min="1" class="qty-input" />
          </div>

          <button type="button" class="btn btn-primary" @click="addToCart">Добавить в корзину</button>
        </div>
      </div>

      <section v-if="relatedProducts.length" class="related-block">
        <div class="section-head">
          <h2>Похожие товары</h2>
          <RouterLink to="/catalog">В каталог</RouterLink>
        </div>

        <div class="mini-grid">
          <RouterLink
            v-for="item in relatedProducts"
            :key="item.id"
            :to="{ name: 'product', params: { id: item.id } }"
            class="mini-card"
          >
            <img :src="item.image" :alt="item.name" />
            <p>{{ item.name }}</p>
            <strong>{{ price(item.price) }}</strong>
          </RouterLink>
        </div>
      </section>
    </template>
  </section>
</template>

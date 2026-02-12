<script setup>
import { computed } from 'vue';
import { useCartStore } from '../stores/cart';

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
  showCategory: {
    type: Boolean,
    default: false,
  },
});

const cart = useCartStore();

const currency = new Intl.NumberFormat('ru-RU', {
  style: 'currency',
  currency: 'RUB',
  maximumFractionDigits: 0,
});

const stockLabel = computed(() => {
  if (props.product.stock <= 0) return 'Нет в наличии';
  if (props.product.stock <= 5) return 'Заканчивается';
  return 'В наличии';
});

const stockClass = computed(() => {
  if (props.product.stock <= 0) return 'badge danger';
  if (props.product.stock <= 5) return 'badge warning';
  return 'badge success';
});

const addToCart = () => {
  cart.addItem(props.product, 1);
};

const price = (value) => currency.format(Number(value || 0));
</script>

<template>
  <article class="product-card">
    <RouterLink :to="{ name: 'product', params: { id: product.id } }" class="product-media">
      <img :src="product.image" :alt="product.name" loading="lazy" />
    </RouterLink>

    <div class="product-content">
      <div class="line-top">
        <span class="sku">{{ product.sku }}</span>
        <span :class="stockClass">{{ stockLabel }}</span>
      </div>

      <RouterLink :to="{ name: 'product', params: { id: product.id } }" class="product-name">
        {{ product.name }}
      </RouterLink>

      <p class="product-meta">{{ product.brand || 'Без бренда' }}</p>
      <p v-if="showCategory" class="product-meta">{{ product.category }}</p>

      <div class="line-bottom">
        <strong>{{ price(product.price) }}</strong>
        <span>{{ product.stock }} шт</span>
      </div>
    </div>

    <div class="product-actions">
      <button type="button" class="btn btn-primary" @click="addToCart">Добавить в корзину</button>
      <RouterLink :to="{ name: 'product', params: { id: product.id } }" class="btn btn-ghost">Подробнее</RouterLink>
    </div>
  </article>
</template>

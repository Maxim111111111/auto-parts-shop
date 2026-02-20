<script setup>
import { computed } from 'vue';
import { useCartStore } from '../stores/cart';
import BaseButton from './BaseButton.vue';

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

const badgeLabel = computed(() => {
  if (props.product.badge) return props.product.badge;
  if (props.product.stock <= 3) return 'Хит';
  if (props.product.stock >= 15) return 'Новинка';
  return '';
});

const isOutOfStock = computed(() => props.product.stock <= 0);

const addToCart = () => {
  if (isOutOfStock.value) return;
  cart.addItem(props.product, 1);
};

const price = (value) => currency.format(Number(value || 0));
</script>

<template>
  <article class="product-card reveal-up">
    <RouterLink :to="{ name: 'product', params: { id: product.id } }" class="product-media">
      <img :src="product.image" :alt="product.name" loading="lazy" />
      <span v-if="badgeLabel" class="product-badge">{{ badgeLabel }}</span>
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

      <div class="product-price-block">
        <strong>{{ price(product.price) }}</strong>
        <span>{{ product.stock }} шт</span>
      </div>
    </div>

    <div class="product-actions">
      <BaseButton variant="primary" size="sm" :disabled="isOutOfStock" @click="addToCart">Добавить</BaseButton>
      <BaseButton variant="ghost" size="sm" :to="{ name: 'product', params: { id: product.id } }">Подробнее</BaseButton>
    </div>
  </article>
</template>

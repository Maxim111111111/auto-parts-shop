<script setup>
import BaseButton from '../components/BaseButton.vue';
import QuantitySelector from '../components/QuantitySelector.vue';
import { useCartStore } from '../stores/cart';

const cart = useCartStore();

const currency = new Intl.NumberFormat('ru-RU', {
  style: 'currency',
  currency: 'RUB',
  maximumFractionDigits: 0,
});

const price = (value) => currency.format(Number(value || 0));

const checkout = () => {
  if (cart.items.length === 0) return;

  alert(`Заказ оформлен на сумму ${price(cart.totalAmount)}. Дальше подключим реальный checkout.`);
  cart.clear();
};
</script>

<template>
  <section class="container page-enter">
    <div class="section-head">
      <h1>Корзина</h1>
      <RouterLink to="/catalog" class="section-link">Продолжить покупки</RouterLink>
    </div>

    <div v-if="cart.items.length === 0" class="empty-box reveal-up">
      <p>Корзина пока пустая.</p>
      <BaseButton :to="{ name: 'catalog' }" variant="primary">Перейти в каталог</BaseButton>
    </div>

    <div v-else class="cart-layout">
      <div class="cart-list">
        <article v-for="item in cart.items" :key="item.id" class="cart-row reveal-up">
          <img :src="item.image" :alt="item.name" loading="lazy" />

          <div class="cart-main">
            <h3>{{ item.name }}</h3>
            <p class="sku">{{ item.sku }}</p>
            <p class="product-meta">{{ price(item.price) }}</p>
          </div>

          <QuantitySelector
            :model-value="item.quantity"
            :min="1"
            @update:model-value="cart.setQuantity(item.id, $event)"
          />

          <div class="cart-total">
            <strong>{{ price(item.price * item.quantity) }}</strong>
            <button type="button" class="link-btn" @click="cart.removeItem(item.id)">Удалить</button>
          </div>
        </article>
      </div>

      <aside class="cart-summary reveal-up">
        <p class="summary-line">Товаров: <strong>{{ cart.totalItems }}</strong></p>
        <p class="summary-total">Итого: <strong>{{ price(cart.totalAmount) }}</strong></p>

        <BaseButton variant="primary" size="lg" block @click="checkout">Оформить заказ</BaseButton>
        <BaseButton variant="ghost" block @click="cart.clear">Очистить корзину</BaseButton>
      </aside>
    </div>
  </section>
</template>

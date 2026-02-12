<script setup>
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
  <section class="container">
    <div class="section-head">
      <h1>Корзина</h1>
      <RouterLink to="/catalog">Продолжить покупки</RouterLink>
    </div>

    <div v-if="cart.items.length === 0" class="empty-box">
      <p>Корзина пуста.</p>
      <RouterLink to="/catalog" class="btn btn-primary">Перейти в каталог</RouterLink>
    </div>

    <div v-else class="cart-layout">
      <div class="cart-list">
        <article v-for="item in cart.items" :key="item.id" class="cart-row">
          <img :src="item.image" :alt="item.name" />

          <div class="cart-main">
            <h3>{{ item.name }}</h3>
            <p class="sku">{{ item.sku }}</p>
            <p>{{ price(item.price) }}</p>
          </div>

          <div class="qty-controls">
            <button type="button" @click="cart.decrement(item.id)">-</button>
            <span>{{ item.quantity }}</span>
            <button type="button" @click="cart.increment(item.id)">+</button>
          </div>

          <div class="cart-total">
            <strong>{{ price(item.price * item.quantity) }}</strong>
            <button type="button" class="link-btn" @click="cart.removeItem(item.id)">Удалить</button>
          </div>
        </article>
      </div>

      <aside class="cart-summary">
        <p>Товаров: <strong>{{ cart.totalItems }}</strong></p>
        <p>Сумма: <strong>{{ price(cart.totalAmount) }}</strong></p>

        <button type="button" class="btn btn-primary" @click="checkout">Оформить заказ</button>
      </aside>
    </div>
  </section>
</template>

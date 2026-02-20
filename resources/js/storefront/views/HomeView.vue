<script setup>
import { computed, onMounted } from 'vue';
import BaseButton from '../components/BaseButton.vue';
import CategoryCard from '../components/CategoryCard.vue';
import ProductCard from '../components/ProductCard.vue';
import { useCatalog } from '../composables/useCatalog';

const { loading, error, categories, products, ensureLoaded } = useCatalog();

const valueProps = [
  {
    icon: '24h',
    title: 'Быстрая логистика',
    text: 'Доставляем в день заказа и подготавливаем самовывоз без очередей.',
  },
  {
    icon: 'OEM',
    title: 'Проверенные бренды',
    text: 'Поставщики с официальной гарантией и понятной историей поставок.',
  },
  {
    icon: 'VIN',
    title: 'Подбор по данным авто',
    text: 'Помогаем подобрать детали по артикулу, категории или параметрам.',
  },
  {
    icon: 'B2B',
    title: 'Удобно для сервиса',
    text: 'Стабильные остатки и быстрое повторение заказов для мастерских.',
  },
];

const steps = [
  {
    title: '1. Найдите позицию',
    text: 'Используйте поиск по SKU, фильтры по бренду и категории.',
  },
  {
    title: '2. Сохраните в корзину',
    text: 'Добавьте нужное количество и проверьте итоговую стоимость.',
  },
  {
    title: '3. Подтвердите заказ',
    text: 'Менеджер свяжется для подтверждения и времени доставки.',
  },
];

const faqItems = [
  {
    q: 'Как проверить совместимость запчасти?',
    a: 'Сверьте SKU в карточке и уточните параметры у менеджера, если есть сомнения.',
  },
  {
    q: 'Есть ли срочная доставка?',
    a: 'Да, для города доступна ускоренная доставка в день заказа.',
  },
  {
    q: 'Можно ли оформить заказ для СТО?',
    a: 'Да, каталог и корзина подходят для регулярных закупок автосервиса.',
  },
];

const featuredProducts = computed(() => products.value.slice(0, 8));
const featuredCategories = computed(() => categories.value.slice(0, 6));

const totalStock = computed(() => products.value.reduce((sum, item) => sum + item.stock, 0));
const inStockCount = computed(() => products.value.filter((item) => item.stock > 0).length);

const topBrands = computed(() => {
  const map = new Map();

  for (const item of products.value) {
    if (!item.brand) continue;
    map.set(item.brand, (map.get(item.brand) || 0) + 1);
  }

  return [...map.entries()]
    .sort((a, b) => b[1] - a[1])
    .slice(0, 7)
    .map(([title]) => title);
});

onMounted(ensureLoaded);
</script>

<template>
  <section class="container page-enter">
    <section class="hero-block reveal-up">
      <div class="hero-content">
        <p class="eyebrow">Clean Tech Catalog</p>
        <h1>Современный каталог автозапчастей для быстрых и точных заказов</h1>
        <p class="hero-lead">
          Технологичный storefront с акцентом на скорость подбора, прозрачные остатки и удобный checkout для клиентов и сервисов.
        </p>

        <div class="hero-actions">
          <BaseButton :to="{ name: 'catalog' }" variant="primary" size="lg">Открыть каталог</BaseButton>
          <BaseButton :to="{ name: 'cart' }" variant="ghost" size="lg">Перейти в корзину</BaseButton>
        </div>
      </div>

      <aside class="hero-glass">
        <p class="hero-caption">Доступно сейчас</p>
        <div class="hero-stats">
          <article>
            <p>Категорий</p>
            <strong>{{ categories.length }}</strong>
          </article>
          <article>
            <p>Товаров</p>
            <strong>{{ products.length }}</strong>
          </article>
          <article>
            <p>В наличии</p>
            <strong>{{ inStockCount }}</strong>
          </article>
          <article>
            <p>Остаток</p>
            <strong>{{ totalStock }}</strong>
          </article>
        </div>

        <div class="trust-list" v-if="topBrands.length > 0">
          <span v-for="brand in topBrands" :key="brand" class="trust-chip">{{ brand }}</span>
        </div>
      </aside>
    </section>

    <section class="value-grid">
      <article v-for="item in valueProps" :key="item.title" class="value-card reveal-up">
        <span class="value-icon">{{ item.icon }}</span>
        <h3>{{ item.title }}</h3>
        <p>{{ item.text }}</p>
      </article>
    </section>

    <section>
      <div class="section-head">
        <h2>Категории запчастей</h2>
        <RouterLink to="/catalog" class="section-link">Перейти в каталог</RouterLink>
      </div>

      <p v-if="error" class="inline-error">{{ error }}</p>
      <p v-else-if="loading" class="inline-info">Загрузка категорий...</p>

      <div v-else class="category-grid">
        <CategoryCard v-for="category in featuredCategories" :key="category.id" :category="category" />
      </div>
    </section>

    <section>
      <div class="section-head">
        <h2>Популярные товары</h2>
        <RouterLink to="/catalog" class="section-link">Смотреть все</RouterLink>
      </div>

      <p v-if="loading" class="inline-info">Загрузка товаров...</p>
      <div v-else class="product-grid">
        <ProductCard v-for="product in featuredProducts" :key="product.id" :product="product" />
      </div>
    </section>

    <section>
      <div class="section-head">
        <h2>Как купить</h2>
      </div>

      <div class="steps-grid">
        <article v-for="item in steps" :key="item.title" class="step-card reveal-up">
          <h3>{{ item.title }}</h3>
          <p>{{ item.text }}</p>
        </article>
      </div>
    </section>

    <section>
      <div class="section-head">
        <h2>Частые вопросы</h2>
      </div>

      <div class="faq-grid">
        <details v-for="item in faqItems" :key="item.q" class="faq-item reveal-up">
          <summary>{{ item.q }}</summary>
          <p>{{ item.a }}</p>
        </details>
      </div>
    </section>

    <section class="final-cta reveal-up">
      <h2>Соберите заказ за пару минут</h2>
      <p>Выбирайте позиции в каталоге и отправляйте заявку без лишних шагов.</p>
      <div class="final-cta-actions">
        <BaseButton :to="{ name: 'catalog' }" variant="primary">Открыть каталог</BaseButton>
        <BaseButton :to="{ name: 'cart' }" variant="ghost">Корзина</BaseButton>
      </div>
    </section>
  </section>
</template>
